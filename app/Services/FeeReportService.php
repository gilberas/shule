<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\Term;
use App\Models\SchoolParent;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeeReportService
{
    /**
     * Get fee collection summary for a term/period.
     */
    public function getCollectionSummary(?int $termId = null, ?string $from = null, ?string $to = null): array
    {
        $query = Payment::with(['student', 'feeStructure']);

        if ($termId) {
            $query->whereHas('student', fn($q) => $q->where('academic_year_id', $termId));
        }
        if ($from) {
            $query->where('payment_date', '>=', $from);
        }
        if ($to) {
            $query->where('payment_date', '<=', $to);
        }

        $payments = $query->get();

        $totalCollected = $payments->where('status', 'paid')->sum('amount');
        $totalPending = $payments->where('status', 'pending')->sum('amount');
        $totalPartial = $payments->where('status', 'partial')->sum('amount');
        $totalWaived = $payments->where('status', 'waived')->sum('amount');

        $byMethod = $payments->where('status', 'paid')->groupBy('payment_method')->map(fn($p) => $p->sum('amount'));
        $byFeeType = $payments->where('status', 'paid')->groupBy('feeStructure.name')->map(fn($p) => $p->sum('amount'));

        return [
            'total_collected' => $totalCollected,
            'total_pending' => $totalPending,
            'total_partial' => $totalPartial,
            'total_waived' => $totalWaived,
            'total_expected' => $totalCollected + $totalPending + $totalPartial,
            'collection_rate' => ($totalCollected + $totalPending + $totalPartial) > 0
                ? round(($totalCollected / ($totalCollected + $totalPending + $totalPartial)) * 100)
                : 0,
            'by_method' => $byMethod,
            'by_fee_type' => $byFeeType,
            'payments' => $payments,
        ];
    }

    /**
     * Get outstanding fees per student.
     */
    public function getOutstandingFees(?int $classLevelId = null): \Illuminate\Support\Collection
    {
        $students = Student::with(['classLevel', 'stream', 'parent'])
            ->where('status', 'active');

        if ($classLevelId) {
            $students->where('class_level_id', $classLevelId);
        }

        return $students->get()->map(function ($student) {
            $totalOwed = FeeStructure::where('is_active', true)
                ->when($student->class_level_id, fn($q) => $q->where(function ($q2) use ($student) {
                    $q2->where('class_level_id', $student->class_level_id)
                        ->orWhereNull('class_level_id');
                }))
                ->sum('amount');

            $totalPaid = Payment::where('student_id', $student->id)
                ->whereIn('status', ['paid', 'partial'])
                ->sum('amount');

            $balance = $totalOwed - $totalPaid;

            return [
                'student_id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'admission_number' => $student->admission_number,
                'class' => $student->classLevel->name ?? 'N/A',
                'stream' => $student->stream->name ?? 'N/A',
                'total_owed' => $totalOwed,
                'total_paid' => $totalPaid,
                'balance' => max(0, $balance),
                'status' => $balance <= 0 ? 'cleared' : ($totalPaid > 0 ? 'partial' : 'unpaid'),
            ];
        });
    }

    /**
     * Generate receipt data for a payment.
     */
    public function getReceiptData(int $paymentId): array
    {
        $payment = Payment::with(['student.classLevel', 'student.stream', 'feeStructure.term', 'feeStructure'])->findOrFail($paymentId);

        return [
            'receipt_number' => $payment->receipt_number ?? $this->generateReceiptNumber($payment->id),
            'date' => $payment->payment_date,
            'student' => [
                'name' => $payment->student->first_name . ' ' . $payment->student->last_name,
                'admission_number' => $payment->student->admission_number,
                'class' => $payment->student->classLevel->name ?? 'N/A',
                'stream' => $payment->student->stream->name ?? 'N/A',
                'parent_name' => $payment->student->parent_name ?? ($payment->student->parent->first_name . ' ' . $payment->student->parent->last_name ?? 'N/A'),
            ],
            'payment' => [
                'amount' => $payment->amount,
                'method' => ucfirst(str_replace('_', ' ', $payment->payment_method)),
                'status' => ucfirst($payment->status),
                'reference' => $payment->receipt_number,
            ],
            'fee' => [
                'name' => $payment->feeStructure->name ?? 'N/A',
                'description' => $payment->feeStructure->description ?? '',
                'amount' => $payment->feeStructure->amount ?? 0,
                'term' => $payment->feeStructure->term->name ?? 'N/A',
                'academic_year' => $payment->feeStructure->term->academicYear->name ?? 'N/A',
            ],
            'notes' => $payment->notes,
        ];
    }

    /**
     * Generate invoice data for a student.
     */
    public function getInvoiceData(int $studentId): array
    {
        $student = Student::with(['classLevel', 'stream', 'parent'])->findOrFail($studentId);

        $feeStructures = FeeStructure::where('is_active', true)
            ->where(function ($q) use ($student) {
                $q->where('class_level_id', $student->class_level_id)
                    ->orWhereNull('class_level_id');
            })
            ->get();

        $payments = Payment::where('student_id', $student->id)->get();
        $totalPaid = $payments->whereIn('status', ['paid', 'partial'])->sum('amount');

        $lineItems = $feeStructures->map(function ($fee) use ($student, $payments) {
            $paidForFee = $payments->where('fee_structure_id', $fee->id)->whereIn('status', ['paid', 'partial'])->sum('amount');
            return [
                'name' => $fee->name,
                'description' => $fee->description,
                'amount' => $fee->amount,
                'paid' => $paidForFee,
                'balance' => max(0, $fee->amount - $paidForFee),
            ];
        });

        $totalExpected = $feeStructures->sum('amount');
        $totalBalance = max(0, $totalExpected - $totalPaid);

        return [
            'invoice_number' => $this->generateInvoiceNumber($student->id),
            'date' => now()->format('Y-m-d'),
            'student' => [
                'name' => $student->first_name . ' ' . $student->last_name,
                'admission_number' => $student->admission_number,
                'class' => $student->classLevel->name ?? 'N/A',
                'stream' => $student->stream->name ?? 'N/A',
                'parent_name' => $student->parent_name ?? ($student->parent->first_name . ' ' . $student->parent->last_name ?? 'N/A'),
                'parent_contact' => $student->parent_contact ?? ($student->parent->phone ?? 'N/A'),
            ],
            'line_items' => $lineItems,
            'total_expected' => $totalExpected,
            'total_paid' => $totalPaid,
            'total_balance' => $totalBalance,
            'status' => $totalBalance <= 0 ? 'Paid in Full' : ($totalPaid > 0 ? 'Partially Paid' : 'Unpaid'),
        ];
    }

    /**
     * Generate fee statement for a student over a period.
     */
    public function getFeeStatement(int $studentId, ?string $from = null, ?string $to = null): array
    {
        $student = Student::with(['classLevel', 'stream', 'parent'])->findOrFail($studentId);

        $payments = Payment::where('student_id', $student->id)
            ->with('feeStructure')
            ->when($from, fn($q) => $q->where('payment_date', '>=', $from))
            ->when($to, fn($q) => $q->where('payment_date', '<=', $to))
            ->orderBy('payment_date')
            ->get();

        $runningBalance = 0;
        $statement = $payments->map(function ($payment) use (&$runningBalance) {
            $runningBalance += $payment->status === 'paid' ? -$payment->amount : 0;
            return [
                'date' => $payment->payment_date,
                'description' => $payment->feeStructure->name ?? 'Fee Payment',
                'receipt' => $payment->receipt_number ?? 'N/A',
                'debit' => $payment->feeStructure->amount ?? 0,
                'credit' => $payment->status === 'paid' ? $payment->amount : 0,
                'balance' => abs($runningBalance),
                'status' => ucfirst($payment->status),
            ];
        });

        return [
            'student' => [
                'name' => $student->first_name . ' ' . $student->last_name,
                'admission_number' => $student->admission_number,
                'class' => $student->classLevel->name ?? 'N/A',
                'stream' => $student->stream->name ?? 'N/A',
            ],
            'period' => [
                'from' => $from ?? 'Beginning',
                'to' => $to ?? now()->format('Y-m-d'),
            ],
            'entries' => $statement,
            'summary' => [
                'total_debit' => $statement->sum('debit'),
                'total_credit' => $statement->sum('credit'),
                'closing_balance' => abs($runningBalance),
            ],
        ];
    }

    /**
     * Payment history for a student (for parent/student view).
     */
    public function getStudentPayments(int $studentId): \Illuminate\Support\Collection
    {
        return Payment::where('student_id', $studentId)
            ->with('feeStructure')
            ->orderByDesc('payment_date')
            ->get();
    }

    /**
     * Daily/weekly/monthly collection summary.
     */
    public function getCollectionTrend(string $period = 'daily', int $days = 30): array
    {
        $trend = [];
        $startDate = now()->subDays($days);

        for ($i = 0; $i <= $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $collected = Payment::where('status', 'paid')
                ->whereDate('payment_date', $date)
                ->sum('amount');
            $count = Payment::where('status', 'paid')
                ->whereDate('payment_date', $date)
                ->count();

            $trend[] = [
                'date' => $date->format('M d'),
                'amount' => round($collected),
                'count' => $count,
            ];
        }

        return $trend;
    }

    /**
     * Generate unique receipt number.
     */
    public function generateReceiptNumber(int $paymentId): string
    {
        return config('school.receipt_prefix', 'REC') . '-' . str_pad($paymentId, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique invoice number.
     */
    public function generateInvoiceNumber(int $studentId): string
    {
        return config('school.invoice_prefix', 'INV') . '-' . str_pad($studentId, 5, '0', STR_PAD_LEFT) . '-' . date('Y');
    }
}
