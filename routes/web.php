<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Volt::route('profile', 'pages.profile')->name('profile');
});

// ─── Role-based dashboards ────────────────────────────────────

Route::middleware(['auth', 'role:super_admin,admin'])->prefix('admin/dashboard')->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        $currentTerm = \App\Models\Term::where('is_current', true)->first();

        $stats = [
            'total_students' => \App\Models\Student::count(),
            'total_teachers' => \App\Models\Teacher::count(),
            'total_parents' => \App\Models\SchoolParent::count(),
            'fee_collected' => \App\Models\Payment::where('status', 'paid')
                ->when($currentTerm, fn($q) => $q->whereHas('student', fn($s) => $s->where('academic_year_id', $currentTerm->academic_year_id)))
                ->sum('amount'),
            'fee_outstanding' => \App\Models\Payment::where('status', 'pending')
                ->when($currentTerm, fn($q) => $q->whereHas('student', fn($s) => $s->where('academic_year_id', $currentTerm->academic_year_id)))
                ->sum('amount'),
            'attendance_today' => \App\Models\Attendance::whereDate('date', today())->count(),
            'attendance_today_present' => \App\Models\Attendance::whereDate('date', today())->where('status', 'present')->count(),
            'grades_entered' => \App\Models\Grade::when($currentTerm, fn($q) => $q->where('term_id', $currentTerm->id))->count(),
            'exams_scheduled' => $currentTerm ? \App\Models\Exam::where('term_id', $currentTerm->id)->count() : 0,
        ];

        // Attendance rate today
        $stats['attendance_rate'] = $stats['attendance_today'] > 0
            ? round(($stats['attendance_today_present'] / $stats['attendance_today']) * 100)
            : 0;

        // Fee collection rate
        $totalExpected = $stats['fee_collected'] + $stats['fee_outstanding'];
        $stats['fee_collection_rate'] = $totalExpected > 0
            ? round(($stats['fee_collected'] / $totalExpected) * 100)
            : 0;

        // Attendance trend (last 30 days)
        $attendanceTrend = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $total = \App\Models\Attendance::whereDate('date', $date)->count();
            $present = \App\Models\Attendance::whereDate('date', $date)->where('status', 'present')->count();
            $attendanceTrend[] = [
                'date' => now()->subDays($i)->format('M d'),
                'pct' => $total > 0 ? round(($present / $total) * 100) : 0,
            ];
        }

        // Class performance
        $classPerformance = \App\Models\ClassLevel::withCount('students as student_count')
            ->orderBy('display_order')
            ->get()
            ->map(function ($cl) use ($currentTerm) {
                $avg = \App\Models\Grade::where('class_level_id', $cl->id)
                    ->when($currentTerm, fn($q) => $q->where('term_id', $currentTerm->id))
                    ->avg('score');
                $avg = $avg ? round($avg) : 0;
                return [
                    'name' => $cl->name,
                    'students' => $cl->student_count,
                    'avg' => $avg,
                    'status' => $avg >= 60 ? 'On Track' : 'Needs Attention',
                ];
            });

        // Recent payments
        $recentPayments = \App\Models\Payment::with(['student', 'feeStructure'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'name' => $p->student->first_name . ' ' . $p->student->last_name,
                'amount' => number_format($p->amount),
                'status' => $p->status,
                'date' => $p->payment_date,
            ]);

        // Recent activity (latest students, payments, grades)
        $recentStudents = \App\Models\Student::latest()->take(3)->get()->map(fn($s) => [
            'type' => 'student',
            'text' => $s->first_name . ' ' . $s->last_name . ' enrolled',
            'date' => $s->created_at?->diffForHumans() ?? '',
        ]);
        $recentGrades = \App\Models\Grade::with('student')->latest()->take(3)->get()->map(fn($g) => [
            'type' => 'grade',
            'text' => ($g->student->first_name ?? '') . ' scored ' . $g->score . '%',
            'date' => $g->created_at?->diffForHumans() ?? '',
        ]);
        $recentActivity = $recentStudents->concat($recentGrades)->sortByDesc('date')->take(5)->values();

        return view('dashboard.admin', compact(
            'user', 'stats', 'attendanceTrend', 'classPerformance',
            'recentPayments', 'recentActivity', 'currentTerm'
        ));
    })->name('dashboard');
});

Route::middleware(['auth', 'role:teacher'])->prefix('teacher/dashboard')->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        $currentTerm = \App\Models\Term::where('is_current', true)->first();

        // Find teacher record by matching name or email
        $teacher = \App\Models\Teacher::where('email', $user->email)->first();

        // Get subjects assigned to this teacher via class_subject
        $subjectIds = $teacher
            ? \DB::table('class_subject')->where('teacher_id', $user->id)->pluck('subject_id')->unique()
            : collect();
        $subjectNames = \App\Models\Subject::whereIn('id', $subjectIds)->pluck('name')->toArray();

        // Get class levels this teacher teaches
        $classLevelIds = $teacher
            ? \DB::table('class_subject')->where('teacher_id', $user->id)->pluck('class_level_id')->unique()
            : collect();
        $classLevels = \App\Models\ClassLevel::whereIn('id', $classLevelIds)->get();

        // Streams for those class levels
        $streams = \App\Models\Stream::whereIn('class_level_id', $classLevelIds)->with('classLevel')->get();

        $totalStudents = $streams->sum(function ($s) {
            return \App\Models\Student::where('stream_id', $s->id)->count();
        });

        $todayAttendance = \App\Models\Attendance::whereDate('date', today())
            ->whereIn('subject_id', $subjectIds)
            ->count();

        $pendingExams = $currentTerm ? \App\Models\Exam::where('exams.term_id', $currentTerm->id)
            ->whereIn('exams.subject_id', $subjectIds)
            ->whereNotExists(function ($query) {
                $query->select(\DB::raw(1))
                    ->from('grades')
                    ->whereColumn('grades.subject_id', 'exams.subject_id')
                    ->whereColumn('grades.class_level_id', 'exams.class_level_id')
                    ->whereColumn('grades.term_id', 'exams.term_id');
            })
            ->count() : 0;

        // Attendance trend for teacher's subjects
        $attendanceTrend = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $total = \App\Models\Attendance::whereDate('date', $date)->whereIn('subject_id', $subjectIds)->count();
            $present = \App\Models\Attendance::whereDate('date', $date)->whereIn('subject_id', $subjectIds)->where('status', 'present')->count();
            $attendanceTrend[] = [
                'date' => now()->subDays($i)->format('M d'),
                'pct' => $total > 0 ? round(($present / $total) * 100) : 0,
            ];
        }

        return view('dashboard.teacher', compact(
            'user', 'teacher', 'streams', 'subjectNames',
            'totalStudents', 'todayAttendance', 'pendingExams', 'attendanceTrend'
        ));
    })->name('teacher.dashboard');
});

Route::middleware(['auth', 'role:accountant'])->prefix('accountant/dashboard')->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        $currentTerm = \App\Models\Term::where('is_current', true)->first();

        $totalCollected = \App\Models\Payment::where('status', 'paid')
            ->when($currentTerm, fn($q) => $q->whereHas('student', fn($s) => $s->where('academic_year_id', $currentTerm->academic_year_id)))
            ->sum('amount');

        $totalExpected = \App\Models\FeeStructure::where('is_active', true)->sum('amount');
        $outstanding = max(0, $totalExpected - $totalCollected);

        $overdueCount = \App\Models\Payment::where('status', 'pending')
            ->when($currentTerm, fn($q) => $q->whereHas('student', fn($s) => $s->where('academic_year_id', $currentTerm->academic_year_id)))
            ->count();

        $todayPayments = \App\Models\Payment::whereDate('payment_date', today())->count();

        // Fee trend over the term
        $feeTrend = [];
        if ($currentTerm) {
            $startDate = $currentTerm->start_date;
            for ($i = 0; $i < 12; $i++) {
                $weekStart = \Carbon\Carbon::parse($startDate)->addWeeks($i)->format('Y-m-d');
                $weekEnd = \Carbon\Carbon::parse($startDate)->addWeeks($i + 1)->format('Y-m-d');
                $collected = \App\Models\Payment::where('status', 'paid')
                    ->whereBetween('payment_date', [$weekStart, $weekEnd])
                    ->sum('amount');
                $feeTrend[] = [
                    'week' => 'W' . ($i + 1),
                    'collected' => round($collected / 1000),
                ];
            }
        }

        // Overdue invoices (students with pending payments)
        $overdueInvoices = \App\Models\Payment::with(['student.classLevel', 'feeStructure'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'student' => $p->student->first_name . ' ' . $p->student->last_name,
                'class' => $p->student->classLevel->name ?? 'N/A',
                'amount' => number_format($p->feeStructure->amount ?? 0),
                'days' => $p->payment_date ? now()->diffInDays($p->payment_date) : 0,
                'status' => 'overdue',
            ]);

        return view('dashboard.accountant', compact(
            'user', 'totalCollected', 'outstanding', 'overdueCount',
            'todayPayments', 'feeTrend', 'overdueInvoices'
        ));
    })->name('accountant.dashboard');
});

Route::middleware(['auth', 'role:parent'])->prefix('parent/dashboard')->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        $parent = \App\Models\SchoolParent::where('user_id', $user->id)->first();
        $children = $parent ? $parent->students()->with(['classLevel', 'stream'])->get() : collect();
        $currentTerm = \App\Models\Term::where('is_current', true)->first();
        $selectedChildId = request('child_id', $children->first()?->id);

        $child = $children->firstWhere('id', $selectedChildId) ?? $children->first();

        // Stats for selected child
        $attendancePct = 0;
        $termAverage = 0;
        $feeBalance = 0;
        $upcomingExams = 0;
        $attendanceTrend = [];
        $recentGrades = collect();

        if ($child && $currentTerm) {
            // Attendance
            $totalDays = \App\Models\Attendance::where('student_id', $child->id)
                ->where('term_id', $currentTerm->id)->count();
            $presentDays = \App\Models\Attendance::where('student_id', $child->id)
                ->where('term_id', $currentTerm->id)->where('status', 'present')->count();
            $attendancePct = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

            // Average grade
            $termAverage = \App\Models\Grade::where('student_id', $child->id)
                ->where('term_id', $currentTerm->id)->avg('score');
            $termAverage = $termAverage ? round($termAverage) : 0;

            // Fee balance
            $feeBalance = \App\Models\Payment::where('student_id', $child->id)
                ->where('status', 'pending')->sum('amount');

            // Upcoming exams
            $upcomingExams = \App\Models\Exam::where('term_id', $currentTerm->id)
                ->where('exam_date', '>=', today())->count();

            // Attendance trend
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $total = \App\Models\Attendance::where('student_id', $child->id)->whereDate('date', $date)->count();
                $present = \App\Models\Attendance::where('student_id', $child->id)->whereDate('date', $date)->where('status', 'present')->count();
                $attendanceTrend[] = [
                    'date' => now()->subDays($i)->format('M d'),
                    'pct' => $total > 0 ? round(($present / $total) * 100) : 0,
                ];
            }

            // Recent grades
            $recentGrades = \App\Models\Grade::where('student_id', $child->id)
                ->where('term_id', $currentTerm->id)
                ->with('subject')
                ->latest()
                ->take(6)
                ->get()
                ->map(fn($g) => [
                    'subject' => $g->subject->name ?? 'N/A',
                    'score' => $g->score,
                    'grade' => $g->grade_letter,
                ]);
        }

        return view('dashboard.parent', compact(
            'user', 'children', 'child', 'selectedChildId',
            'attendancePct', 'termAverage', 'feeBalance', 'upcomingExams',
            'attendanceTrend', 'recentGrades'
        ));
    })->name('parent.dashboard');
});

Route::middleware(['auth', 'role:student'])->prefix('student/dashboard')->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        $student = \App\Models\Student::where('email', $user->email)->first();
        $currentTerm = \App\Models\Term::where('is_current', true)->first();

        $attendancePct = 0;
        $termAverage = 0;
        $feeBalance = 0;
        $nextExam = null;
        $attendanceTrend = [];
        $subjectGrades = collect();

        if ($student && $currentTerm) {
            // Attendance
            $totalDays = \App\Models\Attendance::where('student_id', $student->id)
                ->where('term_id', $currentTerm->id)->count();
            $presentDays = \App\Models\Attendance::where('student_id', $student->id)
                ->where('term_id', $currentTerm->id)->where('status', 'present')->count();
            $attendancePct = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

            // Average grade
            $termAverage = \App\Models\Grade::where('student_id', $student->id)
                ->where('term_id', $currentTerm->id)->avg('score');
            $termAverage = $termAverage ? round($termAverage) : 0;

            // Fee balance
            $feeBalance = \App\Models\Payment::where('student_id', $student->id)
                ->where('status', 'pending')->sum('amount');

            // Next exam
            $nextExam = \App\Models\Exam::where('term_id', $currentTerm->id)
                ->where('class_level_id', $student->class_level_id)
                ->where('exam_date', '>=', today())
                ->orderBy('exam_date')
                ->first();

            // Attendance trend
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $total = \App\Models\Attendance::where('student_id', $student->id)->whereDate('date', $date)->count();
                $present = \App\Models\Attendance::where('student_id', $student->id)->whereDate('date', $date)->where('status', 'present')->count();
                $attendanceTrend[] = [
                    'date' => now()->subDays($i)->format('M d'),
                    'pct' => $total > 0 ? round(($present / $total) * 100) : 0,
                ];
            }

            // Subject grades
            $subjectGrades = \App\Models\Grade::where('student_id', $student->id)
                ->where('term_id', $currentTerm->id)
                ->with('subject')
                ->get()
                ->groupBy(fn($g) => $g->subject->name ?? 'Unknown')
                ->map(function ($grades, $subject) {
                    $avg = round($grades->avg('score'));
                    return [
                        'subject' => $subject,
                        'avg' => $avg,
                        'latest' => $grades->first()->score,
                        'grade' => $grades->first()->grade_letter,
                    ];
                })
                ->values();
        }

        return view('dashboard.student', compact(
            'user', 'student', 'attendancePct', 'termAverage',
            'feeBalance', 'nextExam', 'attendanceTrend', 'subjectGrades'
        ));
    })->name('student.dashboard');
});

// ─── Admin management routes ──────────────────────────────────

Route::middleware(['auth'])->prefix('admin/academic')->group(function () {
    Route::get('/years-terms', function () {
        return view('admin.academic');
    })->name('admin.academic.years-terms');
});

Route::middleware(['auth', 'role:super_admin,admin'])->prefix('admin')->group(function () {
    Route::get('/students', function () {
        return view('admin.students');
    })->name('admin.students');

    Route::get('/teachers', function () {
        return view('admin.teachers');
    })->name('admin.teachers');

    Route::get('/grades', function () {
        return view('admin.grades');
    })->name('admin.grades');

    Route::get('/attendance', function () {
        return view('admin.attendance');
    })->name('admin.attendance');

    Route::get('/parents', function () {
        return view('admin.parents');
    })->name('admin.parents');

    Route::get('/timetable', function () {
        return view('admin.timetable');
    })->name('admin.timetable');

    Route::get('/exams', function () {
        return view('admin.exams');
    })->name('admin.exams');

    Route::get('/library', function () {
        return view('admin.library');
    })->name('admin.library');

    Route::get('/transportation', function () {
        return view('admin.transportation');
    })->name('admin.transportation');

    Route::get('/hostel', function () {
        return view('admin.hostel');
    })->name('admin.hostel');

    Route::get('/messages', function () {
        return view('admin.messages');
    })->name('admin.messages');
});

// ─── Finance management routes (admin + accountant) ───────────

Route::middleware(['auth', 'role:super_admin,admin,accountant'])->prefix('admin')->group(function () {
    Route::get('/fees', function () {
        return view('admin.fees');
    })->name('admin.fees');

    Route::get('/fees/structures', function () {
        return view('admin.fees');
    })->name('admin.fees.structures');

    Route::get('/fees/invoices', function () {
        return view('admin.fees');
    })->name('admin.fees.invoices');
});

// ─── Reports & Documents ──────────────────────────────────────

Route::middleware(['auth', 'role:super_admin,admin,accountant'])->prefix('admin/reports')->group(function () {
    // Reports dashboard
    Route::get('/', function () {
        $user = \Auth::user();
        $currentTerm = \App\Models\Term::where('is_current', true)->first();
        $stats = [
            'total_students' => \App\Models\Student::count(),
            'total_teachers' => \App\Models\Teacher::count(),
            'fee_collected' => \App\Models\Payment::where('status', 'paid')
                ->when($currentTerm, fn($q) => $q->whereHas('student', fn($s) => $s->where('academic_year_id', $currentTerm->academic_year_id)))
                ->sum('amount'),
            'fee_outstanding' => \App\Models\Payment::where('status', 'pending')
                ->when($currentTerm, fn($q) => $q->whereHas('student', fn($s) => $s->where('academic_year_id', $currentTerm->academic_year_id)))
                ->sum('amount'),
            'attendance_rate' => 0,
        ];
        $totalAtt = \App\Models\Attendance::when($currentTerm, fn($q) => $q->where('term_id', $currentTerm->id))->count();
        $presentAtt = \App\Models\Attendance::when($currentTerm, fn($q) => $q->where('term_id', $currentTerm->id))->where('status', 'present')->count();
        $stats['attendance_rate'] = $totalAtt > 0 ? round(($presentAtt / $totalAtt) * 100) : 0;

        return view('admin.reports', compact('user', 'stats'));
    })->name('admin.reports');

    // PDF generation routes
    Route::get('/pdf/receipt', function () {
        $paymentId = request('payment_id');
        if (!$paymentId) return redirect()->route('admin.reports')->with('error', 'Payment ID required');

        $service = new \App\Services\FeeReportService();
        $data = $service->getReceiptData($paymentId);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.receipt', ['data' => $data])
            ->setPaper('a4', 'portrait');
        return $pdf->download("receipt-{$data['receipt_number']}.pdf");
    })->name('admin.reports.pdf.receipt');

    Route::get('/pdf/invoice', function () {
        $studentId = request('student_id');
        if (!$studentId) return redirect()->route('admin.reports')->with('error', 'Student ID required');

        $service = new \App\Services\FeeReportService();
        $data = $service->getInvoiceData($studentId);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.invoice', ['data' => $data])
            ->setPaper('a4', 'portrait');
        return $pdf->download("invoice-{$data['invoice_number']}.pdf");
    })->name('admin.reports.pdf.invoice');

    Route::get('/pdf/fee-statement', function () {
        $studentId = request('student_id');
        if (!$studentId) return redirect()->route('admin.reports')->with('error', 'Student ID required');

        $service = new \App\Services\FeeReportService();
        $data = $service->getFeeStatement($studentId, request('from'), request('to'));
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.fee-statement', ['data' => $data])
            ->setPaper('a4', 'portrait');
        return $pdf->download("fee-statement-{$data['student']['admission_number']}.pdf");
    })->name('admin.reports.pdf.fee-statement');

    Route::get('/pdf/report-card', function () {
        $studentId = request('student_id');
        $termId = request('term_id');
        if (!$studentId || !$termId) return redirect()->route('admin.reports')->with('error', 'Student ID and Term ID required');

        $service = new \App\Services\AcademicReportService();
        $data = $service->getReportCard($studentId, $termId);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.report-card', ['data' => $data])
            ->setPaper('a4', 'portrait');
        return $pdf->download("report-card-{$data['student']['admission_number']}.pdf");
    })->name('admin.reports.pdf.report-card');

    Route::get('/pdf/class-performance', function () {
        $classLevelId = request('class_level_id');
        $termId = request('term_id');
        if (!$classLevelId || !$termId) return redirect()->route('admin.reports')->with('error', 'Class Level ID and Term ID required');

        $service = new \App\Services\AcademicReportService();
        $data = $service->getClassPerformance($classLevelId, $termId);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.class-performance', ['data' => $data])
            ->setPaper('a4', 'portrait');
        return $pdf->download("class-performance-{$data['class_level']['name']}.pdf");
    })->name('admin.reports.pdf.class-performance');

    Route::get('/pdf/attendance-student', function () {
        $studentId = request('student_id');
        $termId = request('term_id');
        if (!$studentId || !$termId) return redirect()->route('admin.reports')->with('error', 'Student ID and Term ID required');

        $service = new \App\Services\AttendanceReportService();
        $data = $service->getStudentAttendance($studentId, $termId);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.attendance-report', ['data' => $data])
            ->setPaper('a4', 'portrait');
        return $pdf->download("attendance-{$data['student']['admission_number']}.pdf");
    })->name('admin.reports.pdf.attendance-student');

    Route::get('/pdf/attendance-class', function () {
        $classLevelId = request('class_level_id');
        $termId = request('term_id');
        if (!$classLevelId || !$termId) return redirect()->route('admin.reports')->with('error', 'Class Level ID and Term ID required');

        $service = new \App\Services\AttendanceReportService();
        $data = $service->getClassAttendance($classLevelId, $termId, request('from'), request('to'));
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.attendance-report', ['data' => $data])
            ->setPaper('a4', 'portrait');
        return $pdf->download("class-attendance-{$data['class_level']['name']}.pdf");
    })->name('admin.reports.pdf.attendance-class');

    Route::get('/pdf/student-register', function () {
        $students = \App\Models\Student::with(['classLevel', 'stream', 'parent'])
            ->orderBy('class_level_id')
            ->orderBy('last_name')
            ->get()
            ->map(fn($s) => [
                'name' => $s->first_name . ' ' . $s->last_name,
                'admission_number' => $s->admission_number,
                'class' => $s->classLevel->name ?? 'N/A',
                'stream' => $s->stream->name ?? 'N/A',
                'parent_name' => $s->parent_name ?? ($s->parent->first_name . ' ' . $s->parent->last_name ?? 'N/A'),
                'contact' => $s->parent_contact ?? ($s->parent->phone ?? 'N/A'),
                'status' => $s->status,
            ]);

        $data = ['students' => $students];
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.student-register', ['data' => $data])
            ->setPaper('a4', 'landscape');
        return $pdf->download('student-register.pdf');
    })->name('admin.reports.pdf.student-register');

    // Excel export routes
    Route::get('/export/students', function () {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentsExport, 'students.xlsx');
    })->name('admin.reports.export.students');

    Route::get('/export/payments', function () {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PaymentsExport(request('status'), request('from'), request('to')),
            'payments.xlsx'
        );
    })->name('admin.reports.export.payments');

    Route::get('/export/grades', function () {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\GradesExport(request('class_level_id'), request('term_id')),
            'grades.xlsx'
        );
    })->name('admin.reports.export.grades');

    Route::get('/export/attendance', function () {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AttendanceExport(request('class_level_id'), request('term_id'), request('from'), request('to')),
            'attendance.xlsx'
        );
    })->name('admin.reports.export.attendance');
});

// ─── Teacher sub-routes ───────────────────────────────────────

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/attendance', function () {
        return view('admin.attendance');
    })->name('teacher.attendance');

    Route::get('/marks', function () {
        return view('admin.grades');
    })->name('teacher.marks');

    Route::get('/timetable', function () {
        return view('admin.timetable');
    })->name('teacher.timetable');

    Route::get('/students', function () {
        return view('admin.students');
    })->name('teacher.students');
});

// ─── Parent sub-routes ────────────────────────────────────────

Route::middleware(['auth', 'role:parent'])->prefix('parent')->group(function () {
    Route::get('/children', function () {
        return redirect()->route('parent.dashboard');
    })->name('parent.children');

    Route::get('/fees', function () {
        return redirect()->route('parent.dashboard');
    })->name('parent.fees');
});

// ─── Student sub-routes ───────────────────────────────────────

Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::get('/grades', function () {
        return redirect()->route('student.dashboard');
    })->name('student.grades');

    Route::get('/attendance', function () {
        return redirect()->route('student.dashboard');
    })->name('student.attendance');

    Route::get('/timetable', function () {
        return redirect()->route('student.dashboard');
    })->name('student.timetable');
});
