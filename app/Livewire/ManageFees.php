<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Payment;
use App\Models\FeeStructure;
use App\Models\Student;

class ManageFees extends Component
{
    public $studentId;
    public $feeStructureId;
    public $amount;
    public $paymentDate;
    public $paymentMethod = 'cash';
    public $status = 'pending';
    public $receiptNumber;
    public $notes;

    public $editPaymentId;
    public $editStudentId;
    public $editFeeStructureId;
    public $editAmount;
    public $editPaymentDate;
    public $editPaymentMethod = 'cash';
    public $editStatus = 'pending';
    public $editReceiptNumber;
    public $editNotes;

    public function render()
    {
        return view('livewire.manage-fees', [
            'payments' => Payment::with(['student', 'feeStructure'])->get(),
            'feeStructures' => FeeStructure::all(),
            'students' => Student::with(['classLevel', 'stream', 'academicYear', 'parent'])->get(),
        ]);
    }

    public function store()
    {
        Payment::create([
            'student_id' => $this->studentId,
            'fee_structure_id' => $this->feeStructureId,
            'amount' => $this->amount,
            'payment_date' => $this->paymentDate,
            'payment_method' => $this->paymentMethod,
            'status' => $this->status,
            'receipt_number' => $this->receiptNumber,
            'notes' => $this->notes,
        ]);

        $this->reset(['studentId', 'feeStructureId', 'amount', 'paymentDate', 'paymentMethod', 'status', 'receiptNumber', 'notes']);
        $this->emit('alert', 'Payment recorded successfully!');
    }

    public function editPayment($id)
    {
        $payment = Payment::find($id);
        $this->editPaymentId = $payment->id;
        $this->editStudentId = $payment->student_id;
        $this->editFeeStructureId = $payment->fee_structure_id;
        $this->editAmount = $payment->amount;
        $this->editPaymentDate = $payment->payment_date;
        $this->editPaymentMethod = $payment->payment_method;
        $this->editStatus = $payment->status;
        $this->editReceiptNumber = $payment->receipt_number;
        $this->editNotes = $payment->notes;
    }

    public function update()
    {
        $payment = Payment::find($this->editPaymentId);
        $payment->update([
            'student_id' => $this->editStudentId,
            'fee_structure_id' => $this->editFeeStructureId,
            'amount' => $this->editAmount,
            'payment_date' => $this->editPaymentDate,
            'payment_method' => $this->editPaymentMethod,
            'status' => $this->editStatus,
            'receipt_number' => $this->editReceiptNumber,
            'notes' => $this->editNotes,
        ]);

        $this->resetInput();
        $this->emit('alert', 'Payment updated successfully!');
    }

    public function deletePayment($id)
    {
        Payment::find($id)->delete();
        $this->emit('alert', 'Payment record deleted successfully!');
    }

    public function resetInput()
    {
        $this->editPaymentId = null;
        $this->editStudentId = null;
        $this->editFeeStructureId = null;
        $this->editAmount = null;
        $this->editPaymentDate = null;
        $this->editPaymentMethod = 'cash';
        $this->editStatus = 'pending';
        $this->editReceiptNumber = '';
        $this->editNotes = '';
    }
}