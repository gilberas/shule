<?php

namespace App\Exports;

use App\Models\Grade;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GradesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $classLevelId;
    protected $termId;

    public function __construct(?int $classLevelId = null, ?int $termId = null)
    {
        $this->classLevelId = $classLevelId;
        $this->termId = $termId;
    }

    public function collection(): \Illuminate\Support\Enumerable
    {
        $query = Grade::with(['student.classLevel', 'subject', 'term'])
            ->orderBy('class_level_id')
            ->orderBy('student_id');

        if ($this->classLevelId) {
            $query->where('class_level_id', $this->classLevelId);
        }
        if ($this->termId) {
            $query->where('term_id', $this->termId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Admission No.',
            'Class',
            'Subject',
            'Exam Type',
            'Score (%)',
            'Grade',
            'Term',
        ];
    }

    public function map($grade): array
    {
        return [
            $grade->student->first_name . ' ' . $grade->student->last_name,
            $grade->student->admission_number ?? 'N/A',
            $grade->classLevel->name ?? 'N/A',
            $grade->subject->name ?? 'N/A',
            $grade->exam_type,
            $grade->score,
            $grade->grade_letter,
            $grade->term->name ?? 'N/A',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
