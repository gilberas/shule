<div class="space-y-6">
    <!-- Payment Form -->
    <div class="rounded-xl border border-border bg-card p-6">
        <h3 class="font-display text-lg font-semibold text-foreground mb-4">Record New Payment</h3>
        <form wire:submit="store" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Student</label>
                    <select wire:model.debounce.300ms="studentId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        <option value="">Select Student</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">{{ $student->first_name . ' ' . $student->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Fee Structure</label>
                    <select wire:model.debounce.300ms="feeStructureId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        <option value="">Select Fee Structure</option>
                        @foreach ($feeStructures as $structure)
                            <option value="{{ $structure->id }}">{{ $structure->name }} - TZS {{ number_format($structure->amount) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Amount Paid</label>
                    <input type="number" wire:model.debounce.300ms="amount" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="0" required>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Payment Date</label>
                    <input type="date" wire:model.debounce.300ms="paymentDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Payment Method</label>
                    <select wire:model.debounce.300ms="paymentMethod" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        <option value="cash" {{ $paymentMethod === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ $paymentMethod === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="check" {{ $paymentMethod === 'check' ? 'selected' : '' }}>Check</option>
                        <option value="card" {{ $paymentMethod === 'card' ? 'selected' : '' }}>Card</option>
                        <option value="online" {{ $paymentMethod === 'online' ? 'selected' : '' }}>Online</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                    <select wire:model.debounce.300ms="status" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        <option value="pending">Pending</option>
                        <option value="partial">Partial</option>
                        <option value="paid">Paid</option>
                        <option value="waived">Waived</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Receipt Number</label>
                    <input type="text" wire:model.debounce.300ms="receiptNumber" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Notes</label>
                    <textarea wire:model.debounce.300ms="notes" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" rows="2"></textarea>
                </div>
            </div>
            <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90 transition-colors">Record Payment</button>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="rounded-xl border border-border bg-card overflow-hidden">
        <div class="px-6 py-4 border-b border-border">
            <h3 class="font-display text-lg font-semibold text-foreground">Payment Records</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-muted-foreground">Student</th>
                        <th class="px-4 py-3 text-left font-medium text-muted-foreground">Fee Structure</th>
                        <th class="px-4 py-3 text-left font-medium text-muted-foreground">Amount</th>
                        <th class="px-4 py-3 text-left font-medium text-muted-foreground">Date</th>
                        <th class="px-4 py-3 text-left font-medium text-muted-foreground">Method</th>
                        <th class="px-4 py-3 text-left font-medium text-muted-foreground">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-muted-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($payments as $payment)
                    <tr class="hover:bg-secondary/30">
                        <td class="px-4 py-3">{{ $payment->student->first_name . ' ' . $payment->student->last_name }}</td>
                        <td class="px-4 py-3">{{ $payment->feeStructure?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 font-mono">TZS {{ number_format($payment->amount) }}</td>
                        <td class="px-4 py-3">{{ $payment->payment_date }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $payment->payment_method === 'cash' ? 'bg-teal/10 text-teal' : 'bg-gold/10 text-gold' }}">
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $payment->status === 'paid' ? 'bg-teal/10 text-teal' : ($payment->status === 'pending' ? 'bg-gold/10 text-gold' : 'bg-clay/10 text-clay') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button wire:click="editPayment({{ $payment->id }})" class="text-xs text-teal hover:underline mr-2">Edit</button>
                            <button wire:click="deletePayment({{ $payment->id }})" wire:confirm="Are you sure?" class="text-xs text-clay hover:underline">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-muted-foreground">No payment records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Payment Modal -->
    @if($editPaymentId)
    <div class="rounded-xl border border-border bg-card p-6">
        <h3 class="font-display text-lg font-semibold text-foreground mb-4">Edit Payment #{{ $editPaymentId }}</h3>
        <form wire:submit="update" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Student</label>
                    <select wire:model.debounce.300ms="editStudentId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        <option value="">Select Student</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ $student->id == $editStudentId ? 'selected' : '' }}>{{ $student->first_name . ' ' . $student->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Fee Structure</label>
                    <select wire:model.debounce.300ms="editFeeStructureId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        <option value="">Select Fee Structure</option>
                        @foreach ($feeStructures as $structure)
                            <option value="{{ $structure->id }}" {{ $structure->id == $editFeeStructureId ? 'selected' : '' }}>{{ $structure->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Amount Paid</label>
                    <input type="number" wire:model.debounce.300ms="editAmount" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="0" required>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Payment Date</label>
                    <input type="date" wire:model.debounce.300ms="editPaymentDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Payment Method</label>
                    <select wire:model.debounce.300ms="editPaymentMethod" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        <option value="cash" {{ $editPaymentMethod === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ $editPaymentMethod === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="check" {{ $editPaymentMethod === 'check' ? 'selected' : '' }}>Check</option>
                        <option value="card" {{ $editPaymentMethod === 'card' ? 'selected' : '' }}>Card</option>
                        <option value="online" {{ $editPaymentMethod === 'online' ? 'selected' : '' }}>Online</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                    <select wire:model.debounce.300ms="editStatus" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        <option value="pending" {{ $editStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="partial" {{ $editStatus === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ $editStatus === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="waived" {{ $editStatus === 'waived' ? 'selected' : '' }}>Waived</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Receipt Number</label>
                    <input type="text" wire:model.debounce.300ms="editReceiptNumber" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Notes</label>
                    <textarea wire:model.debounce.300ms="editNotes" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" rows="2"></textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90 transition-colors">Update Payment</button>
                <button type="button" wire:click="resetInput" class="rounded-lg border border-border px-4 py-2 text-sm font-medium text-foreground hover:bg-secondary transition-colors">Cancel</button>
            </div>
        </form>
    </div>
    @endif
</div>
