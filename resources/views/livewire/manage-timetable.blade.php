<div class="space-y-6">
    <div class="rounded-xl border border-border bg-card p-6">
        <div class="border-b border-border pb-4 mb-6">
            <h4 class="text-lg font-semibold text-foreground">Timetable Management</h4>
        </div>

        <!-- Add Timetable Slot Form -->
        <div class="mb-6">
            <h5 class="text-base font-medium text-foreground mb-4">Add New Timetable Slot</h5>
            <form wire:submit="store">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Class Level</label>
                        <select wire:model.debounce.300ms="classLevelId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Class Level</option>
                            @foreach ($classLevels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Stream</label>
                        <select wire:model.debounce.300ms="streamId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Stream</option>
                            @foreach ($streams as $stream)
                                <option value="{{ $stream->id }}">{{ $stream->name }}</option>
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
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Teacher</label>
                        <select wire:model.debounce.300ms="teacherId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Teacher</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->first_name . ' ' . $teacher->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Day of Week</label>
                        <select wire:model.debounce.300ms="dayOfWeek" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Period</label>
                        <input type="number" wire:model.debounce.300ms="period" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" max="10" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Room</label>
                        <input type="text" wire:model.debounce.300ms="room" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" placeholder="e.g., Room 101">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Academic Year</label>
                        <select wire:model.debounce.300ms="academicYearId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Academic Year</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Term</label>
                        <select wire:model.debounce.300ms="termId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Term</option>
                            @foreach ($terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }} - {{ $term->academicYear->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Add Timetable Slot</button>
                </div>
            </form>
        </div>

        <!-- Timetable Slots Table -->
        <h5 class="text-base font-medium text-foreground mb-4">Timetable Slots</h5>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Class Level</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Stream</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Subject</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Teacher</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Day & Period</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Room</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Academic Year</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Term</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($timetables as $timetable)
                    <tr>
                        <td class="px-4 py-3">{{ $timetable->classLevel->name }}</td>
                        <td class="px-4 py-3">{{ $timetable->stream->name }}</td>
                        <td class="px-4 py-3">{{ $timetable->subject->name }}</td>
                        <td class="px-4 py-3">{{ $timetable->teacher->first_name . ' ' . $timetable->teacher->last_name }}</td>
                        <td class="px-4 py-3">{{ $timetable->day_of_week }} - Period {{ $timetable->period }}</td>
                        <td class="px-4 py-3">{{ $timetable->room ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $timetable->academicYear->name }}</td>
                        <td class="px-4 py-3">{{ $timetable->term->name }}</td>
                        <td class="px-4 py-3">
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="editTimetable({{ $timetable->id }})">Edit</button>
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="deleteTimetable({{ $timetable->id }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Edit Timetable Slot Form -->
        <div class="mt-6">
            <h5 class="text-base font-medium text-foreground mb-4">Edit Timetable Slot {{ $editTimetableId ? '(ID: ' . $editTimetableId . ')' : '' }}</h5>
            @if($editTimetableId)
            <form wire:submit="update">
                <input type="hidden" name="editTimetableId" value="{{ $editTimetableId }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Class Level</label>
                        <select wire:model.debounce.300ms="editClassLevelId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Class Level</option>
                            @foreach ($classLevels as $level)
                                <option value="{{ $level->id }}" {{ $level->id == $editClassLevelId ? 'selected' : '' }}>{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Stream</label>
                        <select wire:model.debounce.300ms="editStreamId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Stream</option>
                            @foreach ($streams as $stream)
                                <option value="{{ $stream->id }}" {{ $stream->id == $editStreamId ? 'selected' : '' }}>{{ $stream->name }}</option>
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
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Teacher</label>
                        <select wire:model.debounce.300ms="editTeacherId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Teacher</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $teacher->id == $editTeacherId ? 'selected' : '' }}>{{ $teacher->first_name . ' ' . $teacher->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Day of Week</label>
                        <select wire:model.debounce.300ms="editDayOfWeek" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="Monday" {{ $editDayOfWeek === 'Monday' ? 'selected' : '' }}>Monday</option>
                            <option value="Tuesday" {{ $editDayOfWeek === 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                            <option value="Wednesday" {{ $editDayOfWeek === 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                            <option value="Thursday" {{ $editDayOfWeek === 'Thursday' ? 'selected' : '' }}>Thursday</option>
                            <option value="Friday" {{ $editDayOfWeek === 'Friday' ? 'selected' : '' }}>Friday</option>
                            <option value="Saturday" {{ $editDayOfWeek === 'Saturday' ? 'selected' : '' }}>Saturday</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Period</label>
                        <input type="number" wire:model.debounce.300ms="editPeriod" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" max="10" required value="{{ $editPeriod }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Room</label>
                        <input type="text" wire:model.debounce.300ms="editRoom" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" value="{{ $editRoom }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Academic Year</label>
                        <select wire:model.debounce.300ms="editAcademicYearId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Academic Year</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $year->id == $editAcademicYearId ? 'selected' : '' }}>{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Term</label>
                        <select wire:model.debounce.300ms="editTermId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Term</option>
                            @foreach ($terms as $term)
                                <option value="{{ $term->id }}" {{ $term->id == $editTermId ? 'selected' : '' }}>{{ $term->name }} - {{ $term->academicYear->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Update Timetable Slot</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
