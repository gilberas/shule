<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $status;
    protected $from;
    protected $to;

    public function __construct(?string $status = null, ?string $from = null, ?string $to = null)
    {
        $this->status = $status;
        $this->from = $from;
        $this->to = $to;
    }

    public function collection(): \Illuminate\Support\Enumerable
    {
        $query = Payment::with(['student.classLevel', 'feeStructure'])
            ->orderByDesc('payment_date');

        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->from) {
            $query->where('payment_date', '>=', $this->from);
        }
        if ($this->to) {
            $query->where('payment_date', '<=', $this->to);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Receipt No.',
            'Student Name',
            'Admission No.',
            'Class',
            'Fee Type',
            'Amount (TSh)',
            'Payment Date',
            'Payment Method',
            'Status',
            'Notes',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->receipt_number ?? 'N/A',
            $payment->student->first_name . ' ' . $payment->student->last_name,
            $payment->student->admission_number ?? 'N/A',
            $payment->student->classLevel->name ?? 'N/A',
            $payment->feeStructure->name ?? 'N/A',
            $payment->amount,
            \Carbon\Carbon::parse($payment->payment_date)->format('d M Y'),
            ucfirst(str_replace('_', ' ', $payment->payment_method)),
            ucfirst($payment->status),
            $payment->notes ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
