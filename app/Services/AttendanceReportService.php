<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassLevel;
use App\Models\Subject;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceReportService
{
    /**
     * Student attendance report for a term.
     */
    public function getStudentAttendance(int $studentId, int $termId): array
    {
        $student = Student::with(['classLevel', 'stream'])->findOrFail($studentId);
        $term = Term::with('academicYear')->findOrFail($termId);

        $attendance = Attendance::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->get();

        $total = $attendance->count();
        $present = $attendance->where('status', 'present')->count();
        $absent = $attendance->where('status', 'absent')->count();
        $excused = $attendance->where('status', 'excused')->count();

        // By subject
        $bySubject = $attendance->groupBy(fn($a) => $a->subject->name ?? 'Unknown')
            ->map(function ($records, $subject) {
                $total = $records->count();
                $present = $records->where('status', 'present')->count();
                return [
                    'subject' => $subject,
                    'total' => $total,
                    'present' => $present,
                    'absent' => $records->where('status', 'absent')->count(),
                    'excused' => $records->where('status', 'excused')->count(),
                    'rate' => $total > 0 ? round(($present / $total) * 100) : 0,
                ];
            })
            ->values();

        // Daily attendance for the term
        $daily = $attendance->groupBy('date')->map(function ($records, $date) {
            return [
                'date' => $date,
                'status' => $records->first()->status,
            ];
        })->values();

        return [
            'student' => [
                'name' => $student->first_name . ' ' . $student->last_name,
                'admission_number' => $student->admission_number,
                'class' => $student->classLevel->name ?? 'N/A',
                'stream' => $student->stream->name ?? 'N/A',
            ],
            'term' => [
                'name' => $term->name,
                'academic_year' => $term->academicYear->name ?? 'N/A',
            ],
            'summary' => [
                'total_days' => $total,
                'present' => $present,
                'absent' => $absent,
                'excused' => $excused,
                'attendance_rate' => $total > 0 ? round(($present / $total) * 100) : 0,
            ],
            'by_subject' => $bySubject,
            'daily' => $daily,
        ];
    }

    /**
     * Class attendance summary for a date range.
     */
    public function getClassAttendance(int $classLevelId, int $termId, ?string $from = null, ?string $to = null): array
    {
        $classLevel = ClassLevel::findOrFail($classLevelId);
        $term = Term::with('academicYear')->findOrFail($termId);

        $query = Attendance::where('class_level_id', $classLevelId)
            ->where('term_id', $termId);

        if ($from) {
            $query->where('date', '>=', $from);
        }
        if ($to) {
            $query->where('date', '<=', $to);
        }

        $attendance = $query->get();

        // By student
        $students = Student::where('class_level_id', $classLevelId)
            ->where('status', 'active')
            ->with('stream')
            ->get();

        $studentData = $students->map(function ($student) use ($attendance) {
            $studentRecords = $attendance->where('student_id', $student->id);
            $total = $studentRecords->count();
            $present = $studentRecords->where('status', 'present')->count();
            return [
                'student_id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'admission_number' => $student->admission_number,
                'stream' => $student->stream->name ?? 'N/A',
                'total' => $total,
                'present' => $present,
                'absent' => $studentRecords->where('status', 'absent')->count(),
                'excused' => $studentRecords->where('status', 'excused')->count(),
                'rate' => $total > 0 ? round(($present / $total) * 100) : 0,
            ];
        })->sortByDesc('rate')->values();

        $totalRecords = $attendance->count();
        $totalPresent = $attendance->where('status', 'present')->count();

        return [
            'class_level' => [
                'name' => $classLevel->name,
                'id' => $classLevel->id,
            ],
            'term' => [
                'name' => $term->name,
                'academic_year' => $term->academicYear->name ?? 'N/A',
            ],
            'period' => [
                'from' => $from ?? 'Beginning',
                'to' => $to ?? now()->format('Y-m-d'),
            ],
            'students' => $studentData,
            'summary' => [
                'total_students' => $students->count(),
                'total_records' => $totalRecords,
                'total_present' => $totalPresent,
                'total_absent' => $attendance->where('status', 'absent')->count(),
                'total_excused' => $attendance->where('status', 'excused')->count(),
                'overall_rate' => $totalRecords > 0 ? round(($totalPresent / $totalRecords) * 100) : 0,
            ],
        ];
    }

    /**
     * Monthly attendance overview.
     */
    public function getMonthlyOverview(int $termId, ?int $classLevelId = null): array
    {
        $term = Term::with('academicYear')->findOrFail($termId);

        $query = Attendance::where('term_id', $termId);
        if ($classLevelId) {
            $query->where('class_level_id', $classLevelId);
        }

        $attendance = $query->get();

        $months = $attendance->groupBy(fn($a) => Carbon::parse($a->date)->format('Y-m'))
            ->map(function ($records, $month) {
                $total = $records->count();
                $present = $records->where('status', 'present')->count();
                return [
                    'month' => Carbon::parse($month . '-01')->format('F Y'),
                    'total' => $total,
                    'present' => $present,
                    'absent' => $records->where('status', 'absent')->count(),
                    'excused' => $records->where('status', 'excused')->count(),
                    'rate' => $total > 0 ? round(($present / $total) * 100) : 0,
                ];
            })
            ->values();

        return [
            'term' => [
                'name' => $term->name,
                'academic_year' => $term->academicYear->name ?? 'N/A',
            ],
            'months' => $months,
        ];
    }

    /**
     * Attendance trend for charts.
     */
    public function getAttendanceTrend(int $termId, int $days = 30): array
    {
        $trend = [];
        $startDate = Term::find($termId)?->start_date ? Carbon::parse(Term::find($termId)->start_date) : now()->subDays($days);

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            if ($date->isAfter(now())) break;

            $total = Attendance::where('term_id', $termId)->whereDate('date', $date)->count();
            $present = Attendance::where('term_id', $termId)->whereDate('date', $date)->where('status', 'present')->count();

            $trend[] = [
                'date' => $date->format('M d'),
                'rate' => $total > 0 ? round(($present / $total) * 100) : 0,
                'total' => $total,
                'present' => $present,
            ];
        }

        return $trend;
    }
}
