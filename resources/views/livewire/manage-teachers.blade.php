<div class="space-y-6">
    <div class="rounded-xl border border-border bg-card p-6">
        <h2 class="text-lg font-semibold text-foreground mb-4">Add New Teacher</h2>
        <form wire:submit="store">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">First Name</label>
                    <input type="text" wire:model.debounce.300ms="firstName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Last Name</label>
                    <input type="text" wire:model.debounce.300ms="lastName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Email</label>
                    <input type="email" wire:model.debounce.300ms="email" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Phone</label>
                    <input type="text" wire:model.debounce.300ms="phone" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-foreground mb-1">Address</label>
                <input type="text" wire:model.debounce.300ms="address" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Subject</label>
                    <select wire:model.debounce.300ms="subjectId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        <option value="">Select Subject</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Hire Date</label>
                    <input type="date" wire:model.debounce.300ms="hireDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                <select wire:model.debounce.300ms="status" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="on_leave">On Leave</option>
                </select>
            </div>

            <div class="mt-6">
                <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90 transition-colors w-full">Add Teacher</button>
            </div>
        </form>
    </div>

    <div class="rounded-xl border border-border bg-card p-6">
        <h2 class="text-lg font-semibold text-foreground mb-4">Registered Teachers</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Name</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Email</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Subject</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Hire Date</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($teachers as $teacher)
                    <tr>
                        <td class="px-4 py-3">{{ $teacher->first_name . ' ' . $teacher->last_name }}</td>
                        <td class="px-4 py-3">{{ $teacher->email }}</td>
                        <td class="px-4 py-3">{{ $teacher->subject ? $teacher->subject->name : 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $teacher->hire_date }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $teacher->status === 'active' ? 'bg-emerald-50 text-emerald-700' : ($teacher->status === 'inactive' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">
                                {{ ucfirst($teacher->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button class="rounded-lg border border-border px-4 py-2 text-sm font-medium text-foreground hover:bg-secondary transition-colors" wire:click="editTeacher({{ $teacher->id }})">Edit</button>
                            <button class="rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600 transition-colors ml-2" wire:click="deleteTeacher({{ $teacher->id }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($editTeacherId)
    <div class="rounded-xl border border-border bg-card p-6">
        <h2 class="text-lg font-semibold text-foreground mb-4">Edit Teacher (ID: {{ $editTeacherId }})</h2>
        <form wire:submit="update">
            <input type="hidden" name="editTeacherId" value="{{ $editTeacherId }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">First Name</label>
                    <input type="text" wire:model.debounce.300ms="editFirstName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editFirstName }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Last Name</label>
                    <input type="text" wire:model.debounce.300ms="editLastName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editLastName }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Email</label>
                    <input type="email" wire:model.debounce.300ms="editEmail" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editEmail }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Phone</label>
                    <input type="text" wire:model.debounce.300ms="editPhone" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" value="{{ $editPhone }}">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-foreground mb-1">Address</label>
                <input type="text" wire:model.debounce.300ms="editAddress" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" value="{{ $editAddress }}">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Subject</label>
                    <select wire:model.debounce.300ms="editSubjectId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $subject->id == $editSubjectId ? 'selected' : '' }}>{{ $subject->name }} ({{ $subject->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Hire Date</label>
                    <input type="date" wire:model.debounce.300ms="editHireDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editHireDate }}">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                <select wire:model.debounce.300ms="editStatus" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                    <option value="active" {{ $editStatus === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $editStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="on_leave" {{ $editStatus === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                </select>
            </div>

            <div class="mt-6">
                <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90 transition-colors w-full">Update Teacher</button>
            </div>
        </form>
    </div>
    @endif
</div>
