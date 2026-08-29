<div class="space-y-6">
    <div class="rounded-xl border border-border bg-card p-6">
        <h2 class="text-lg font-semibold text-foreground mb-4">Take Attendance</h2>

        <div class="mb-6">
            <h3 class="text-sm font-medium text-foreground mb-3">Record Attendance</h3>
            <form wire:submit="store">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Student</label>
                        <select wire:model.debounce.300ms="studentId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->first_name . ' ' . $student->last_name }} ({{ $student->email }})</option>
                            @endforeach
                        </select>
                    </div>
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
                        <label class="block text-sm font-medium text-foreground mb-1">Class Level</label>
                        <select wire:model.debounce.300ms="classLevelId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Class Level</option>
                            @foreach ($classLevels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Term</label>
                        <select wire:model.debounce.300ms="termId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Term</option>
                            @foreach ($terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }} - {{ $term->academicYear->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Date</label>
                        <input type="date" wire:model.debounce.300ms="attendanceDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                        <select wire:model.debounce.300ms="status" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="present" selected>Present</option>
                            <option value="absent">Absent</option>
                            <option value="excused">Excused</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Record Attendance</button>
                </div>
            </form>
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-medium text-foreground mb-3">Attendance Records</h3>
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left">Student</th>
                        <th class="px-4 py-3 text-left">Subject</th>
                        <th class="px-4 py-3 text-left">Class Level</th>
                        <th class="px-4 py-3 text-left">Term</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($attendances as $attendance)
                    <tr>
                        <td class="px-4 py-3">{{ $attendance->student->first_name . ' ' . $attendance->student->last_name }}</td>
                        <td class="px-4 py-3">{{ $attendance->subject->name }}</td>
                        <td class="px-4 py-3">{{ $attendance->classLevel->name }}</td>
                        <td class="px-4 py-3">{{ $attendance->term->name }} ({{ $attendance->term->academicYear->name }})</td>
                        <td class="px-4 py-3">{{ $attendance->date }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : ($attendance->status === 'absent' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800" wire:click="editAttendance({{ $attendance->id }})">Edit</button>
                            <button class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-red-100 text-red-800" wire:click="deleteAttendance({{ $attendance->id }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-medium text-foreground mb-3">Edit Attendance {{ $editAttendanceId ? '(ID: ' . $editAttendanceId . ')' : '' }}</h3>
            @if($editAttendanceId)
            <form wire:submit="update">
                <input type="hidden" name="editAttendanceId" value="{{ $editAttendanceId }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                        <label class="block text-sm font-medium text-foreground mb-1">Subject</label>
                        <select wire:model.debounce.300ms="editSubjectId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $subject->id == $editSubjectId ? 'selected' : '' }}>{{ $subject->name }} ({{ $subject->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Class Level</label>
                        <select wire:model.debounce.300ms="editClassLevelId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Class Level</option>
                            @foreach ($classLevels as $level)
                                <option value="{{ $level->id }}" {{ $level->id == $editClassLevelId ? 'selected' : '' }}>{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Term</label>
                        <select wire:model.debounce.300ms="editTermId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Term</option>
                            @foreach ($terms as $term)
                                <option value="{{ $term->id }}" {{ $term->id == $editTermId ? 'selected' : '' }}>{{ $term->name }} - {{ $term->academicYear->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Date</label>
                        <input type="date" wire:model.debounce.300ms="editAttendanceDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editAttendanceDate }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                        <select wire:model.debounce.300ms="editStatus" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="present" {{ $editStatus === 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ $editStatus === 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="excused" {{ $editStatus === 'excused' ? 'selected' : '' }}>Excused</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Update Attendance</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
