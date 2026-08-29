<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\Exam;
use App\Models\Student;
use App\Models\ClassLevel;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Support\Facades\DB;

class AcademicReportService
{
    /**
     * Report card data for a student for a term.
     */
    public function getReportCard(int $studentId, int $termId): array
    {
        $student = Student::with(['classLevel', 'stream', 'parent'])->findOrFail($studentId);
        $term = Term::with('academicYear')->findOrFail($termId);

        $grades = Grade::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->with('subject')
            ->get();

        $subjectData = $grades->groupBy(fn($g) => $g->subject->name ?? 'Unknown')
            ->map(function ($subjectGrades, $subjectName) {
                $avgScore = $subjectGrades->avg('score');
                $latest = $subjectGrades->first();
                return [
                    'subject' => $subjectName,
                    'scores' => $subjectGrades->map(fn($g) => [
                        'exam_type' => $g->exam_type,
                        'score' => $g->score,
                        'grade' => $g->grade_letter,
                    ])->toArray(),
                    'average' => round($avgScore, 1),
                    'grade' => $this->getGradeLetter($avgScore),
                    'remark' => $this->getGradeRemark($avgScore),
                ];
            })
            ->values();

        $overallAverage = $grades->avg('score');
        $classAverage = Grade::where('class_level_id', $student->class_level_id)
            ->where('term_id', $termId)
            ->avg('score');

        // Class position
        $studentAverages = Grade::where('class_level_id', $student->class_level_id)
            ->where('term_id', $termId)
            ->select('student_id', DB::raw('AVG(score) as avg_score'))
            ->groupBy('student_id')
            ->orderByDesc('avg_score')
            ->get();

        $position = $studentAverages->pluck('student_id')->search($studentId);
        $position = $position !== false ? $position + 1 : null;

        return [
            'student' => [
                'name' => $student->first_name . ' ' . $student->last_name,
                'admission_number' => $student->admission_number,
                'class' => $student->classLevel->name ?? 'N/A',
                'stream' => $student->stream->name ?? 'N/A',
                'parent_name' => $student->parent_name ?? ($student->parent->first_name . ' ' . $student->parent->last_name ?? 'N/A'),
            ],
            'term' => [
                'name' => $term->name,
                'academic_year' => $term->academicYear->name ?? 'N/A',
                'start_date' => $term->start_date,
                'end_date' => $term->end_date,
            ],
            'subjects' => $subjectData,
            'summary' => [
                'overall_average' => round($overallAverage ?? 0, 1),
                'overall_grade' => $this->getGradeLetter($overallAverage ?? 0),
                'class_average' => round($classAverage ?? 0, 1),
                'position' => $position,
                'total_students' => $studentAverages->count(),
                'remark' => $this->getGradeRemark($overallAverage ?? 0),
            ],
        ];
    }

    /**
     * Class performance summary for a class level in a term.
     */
    public function getClassPerformance(int $classLevelId, int $termId): array
    {
        $classLevel = ClassLevel::findOrFail($classLevelId);
        $term = Term::with('academicYear')->findOrFail($termId);

        $students = Student::where('class_level_id', $classLevelId)
            ->where('status', 'active')
            ->with('stream')
            ->get();

        $studentPerformance = $students->map(function ($student) use ($termId) {
            $grades = Grade::where('student_id', $student->id)
                ->where('term_id', $termId)
                ->get();

            $avg = $grades->avg('score');
            return [
                'student_id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'admission_number' => $student->admission_number,
                'stream' => $student->stream->name ?? 'N/A',
                'average' => $avg ? round($avg, 1) : null,
                'grade' => $avg !== null ? $this->getGradeLetter($avg) : 'N/A',
                'subjects_count' => $grades->count(),
            ];
        })->sortByDesc('average')->values();

        // Subject averages
        $subjects = Subject::whereHas('classSubjects', fn($q) => $q->where('class_level_id', $classLevelId))->get();
        $subjectPerformance = $subjects->map(function ($subject) use ($classLevelId, $termId) {
            $avg = Grade::where('class_level_id', $classLevelId)
                ->where('subject_id', $subject->id)
                ->where('term_id', $termId)
                ->avg('score');
            return [
                'subject' => $subject->name,
                'average' => $avg ? round($avg, 1) : null,
                'grade' => $avg !== null ? $this->getGradeLetter($avg) : 'N/A',
            ];
        });

        $classAverage = $studentPerformance->whereNotNull('average')->avg('average');

        return [
            'class_level' => [
                'name' => $classLevel->name,
                'id' => $classLevel->id,
            ],
            'term' => [
                'name' => $term->name,
                'academic_year' => $term->academicYear->name ?? 'N/A',
            ],
            'students' => $studentPerformance,
            'subject_performance' => $subjectPerformance,
            'summary' => [
                'total_students' => $students->count(),
                'students_graded' => $studentPerformance->whereNotNull('average')->count(),
                'class_average' => $classAverage ? round($classAverage, 1) : 0,
                'class_grade' => $classAverage !== null ? $this->getGradeLetter($classAverage) : 'N/A',
                'highest_average' => $studentPerformance->whereNotNull('average')->max('average'),
                'lowest_average' => $studentPerformance->whereNotNull('average')->min('average'),
                'pass_rate' => $studentPerformance->whereNotNull('average')->count() > 0
                    ? round(($studentPerformance->where('average', '>=', 50)->count() / $studentPerformance->whereNotNull('average')->count()) * 100)
                    : 0,
            ],
        ];
    }

    /**
     * Subject performance across classes.
     */
    public function getSubjectPerformance(int $subjectId, int $termId): array
    {
        $subject = Subject::findOrFail($subjectId);
        $term = Term::with('academicYear')->findOrFail($termId);

        $classLevels = ClassLevel::whereHas('students')
            ->whereHas('grades', fn($q) => $q->where('subject_id', $subjectId)->where('term_id', $termId))
            ->get();

        $classData = $classLevels->map(function ($cl) use ($subjectId, $termId) {
            $grades = Grade::where('class_level_id', $cl->id)
                ->where('subject_id', $subjectId)
                ->where('term_id', $termId);

            return [
                'class' => $cl->name,
                'average' => round($grades->avg('score') ?? 0, 1),
                'highest' => $grades->max('score'),
                'lowest' => $grades->min('score'),
                'students' => $grades->count('student_id'),
                'pass_rate' => $grades->count() > 0
                    ? round(($grades->where('grade_letter', '!=', 'F')->count() / $grades->count()) * 100)
                    : 0,
            ];
        });

        return [
            'subject' => [
                'name' => $subject->name,
                'code' => $subject->code,
            ],
            'term' => [
                'name' => $term->name,
                'academic_year' => $term->academicYear->name ?? 'N/A',
            ],
            'classes' => $classData,
            'overall' => [
                'average' => round(Grade::where('subject_id', $subjectId)->where('term_id', $termId)->avg('score') ?? 0, 1),
                'total_students' => Grade::where('subject_id', $subjectId)->where('term_id', $termId)->distinct('student_id')->count('student_id'),
            ],
        ];
    }

    /**
     * Exam results for a specific exam.
     */
    public function getExamResults(int $examId): array
    {
        $exam = Exam::with(['subject', 'classLevel', 'term'])->findOrFail($examId);

        $grades = Grade::where('class_level_id', $exam->class_level_id)
            ->where('subject_id', $exam->subject_id)
            ->where('term_id', $exam->term_id)
            ->where('exam_type', $exam->name)
            ->with('student')
            ->get()
            ->map(fn($g) => [
                'student_id' => $g->student_id,
                'name' => $g->student->first_name . ' ' . $g->student->last_name,
                'admission_number' => $g->student->admission_number,
                'score' => $g->score,
                'grade' => $g->grade_letter,
                'remark' => $this->getGradeRemark($g->score),
                'pass' => $g->score >= ($exam->pass_marks ?? 50),
            ]);

        return [
            'exam' => [
                'name' => $exam->name,
                'subject' => $exam->subject->name,
                'class' => $exam->classLevel->name,
                'term' => $exam->term->name,
                'date' => $exam->exam_date,
                'total_marks' => $exam->total_marks,
                'pass_marks' => $exam->pass_marks,
            ],
            'results' => $grades->sortByDesc('score')->values(),
            'summary' => [
                'total_students' => $grades->count(),
                'average' => round($grades->avg('score') ?? 0, 1),
                'highest' => $grades->max('score'),
                'lowest' => $grades->min('score'),
                'pass_count' => $grades->where('pass', true)->count(),
                'fail_count' => $grades->where('pass', false)->count(),
                'pass_rate' => $grades->count() > 0
                    ? round(($grades->where('pass', true)->count() / $grades->count()) * 100)
                    : 0,
            ],
        ];
    }

    /**
     * Student academic history across terms.
     */
    public function getStudentHistory(int $studentId): array
    {
        $student = Student::with(['classLevel', 'stream'])->findOrFail($studentId);

        $terms = Term::with('academicYear')->orderByDesc('id')->get();

        $termResults = $terms->map(function ($term) use ($studentId) {
            $grades = Grade::where('student_id', $studentId)
                ->where('term_id', $term->id)
                ->with('subject')
                ->get();

            if ($grades->isEmpty()) {
                return null;
            }

            return [
                'term' => $term->name,
                'academic_year' => $term->academicYear->name ?? 'N/A',
                'average' => round($grades->avg('score'), 1),
                'grade' => $this->getGradeLetter($grades->avg('score')),
                'subjects' => $grades->count(),
            ];
        })->filter()->values();

        return [
            'student' => [
                'name' => $student->first_name . ' ' . $student->last_name,
                'admission_number' => $student->admission_number,
                'class' => $student->classLevel->name ?? 'N/A',
                'stream' => $student->stream->name ?? 'N/A',
            ],
            'history' => $termResults,
        ];
    }

    /**
     * Convert score to grade letter.
     */
    public function getGradeLetter(float $score): string
    {
        $scale = config('school.grading_scale', [
            ['min' => 80, 'max' => 100, 'grade' => 'A'],
            ['min' => 70, 'max' => 79, 'grade' => 'B'],
            ['min' => 60, 'max' => 69, 'grade' => 'C'],
            ['min' => 50, 'max' => 59, 'grade' => 'D'],
            ['min' => 0, 'max' => 49, 'grade' => 'F'],
        ]);

        foreach ($scale as $level) {
            if ($score >= $level['min'] && $score <= $level['max']) {
                return $level['grade'];
            }
        }
        return 'F';
    }

    /**
     * Get grade remark.
     */
    public function getGradeRemark(float $score): string
    {
        $scale = config('school.grading_scale', [
            ['min' => 80, 'max' => 100, 'remark' => 'Excellent'],
            ['min' => 70, 'max' => 79, 'remark' => 'Very Good'],
            ['min' => 60, 'max' => 69, 'remark' => 'Good'],
            ['min' => 50, 'max' => 59, 'remark' => 'Average'],
            ['min' => 0, 'max' => 49, 'remark' => 'Fail'],
        ]);

        foreach ($scale as $level) {
            if ($score >= $level['min'] && $score <= $level['max']) {
                return $level['remark'];
            }
        }
        return 'Fail';
    }
}
