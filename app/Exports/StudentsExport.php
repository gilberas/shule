<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $classLevelId;

    public function __construct(?int $classLevelId = null)
    {
        $this->classLevelId = $classLevelId;
    }

    public function collection(): \Illuminate\Support\Enumerable
    {
        $query = Student::with(['classLevel', 'stream', 'parent', 'academicYear'])
            ->orderBy('class_level_id')
            ->orderBy('last_name');

        if ($this->classLevelId) {
            $query->where('class_level_id', $this->classLevelId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Admission No.',
            'First Name',
            'Last Name',
            'Email',
            'Class',
            'Stream',
            'Academic Year',
            'Date of Birth',
            'Parent/Guardian',
            'Parent Contact',
            'Address',
            'Enrollment Date',
            'Status',
        ];
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->admission_number ?? 'N/A',
            $student->first_name,
            $student->last_name,
            $student->email,
            $student->classLevel->name ?? 'N/A',
            $student->stream->name ?? 'N/A',
            $student->academicYear->name ?? 'N/A',
            $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') : 'N/A',
            $student->parent_name ?: ($student->parent ? $student->parent->first_name . ' ' . $student->parent->last_name : 'N/A'),
            $student->parent_contact ?: ($student->parent->phone ?? 'N/A'),
            $student->address ?? 'N/A',
            $student->enrollment_date ? \Carbon\Carbon::parse($student->enrollment_date)->format('d M Y') : 'N/A',
            ucfirst($student->status),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
