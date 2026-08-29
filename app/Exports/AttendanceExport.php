<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $classLevelId;
    protected $termId;
    protected $from;
    protected $to;

    public function __construct(?int $classLevelId = null, ?int $termId = null, ?string $from = null, ?string $to = null)
    {
        $this->classLevelId = $classLevelId;
        $this->termId = $termId;
        $this->from = $from;
        $this->to = $to;
    }

    public function collection(): \Illuminate\Support\Enumerable
    {
        $query = Attendance::with(['student.classLevel', 'subject', 'term'])
            ->orderBy('date')
            ->orderBy('student_id');

        if ($this->classLevelId) {
            $query->where('class_level_id', $this->classLevelId);
        }
        if ($this->termId) {
            $query->where('term_id', $this->termId);
        }
        if ($this->from) {
            $query->where('date', '>=', $this->from);
        }
        if ($this->to) {
            $query->where('date', '<=', $this->to);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Student Name',
            'Admission No.',
            'Class',
            'Subject',
            'Status',
            'Term',
        ];
    }

    public function map($attendance): array
    {
        return [
            \Carbon\Carbon::parse($attendance->date)->format('d M Y'),
            $attendance->student->first_name . ' ' . $attendance->student->last_name,
            $attendance->student->admission_number ?? 'N/A',
            $attendance->classLevel->name ?? 'N/A',
            $attendance->subject->name ?? 'N/A',
            ucfirst($attendance->status),
            $attendance->term->name ?? 'N/A',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
