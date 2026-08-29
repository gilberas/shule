<div class="space-y-6">
    <div class="rounded-xl border border-border bg-card p-6">
        <h2 class="text-xl font-semibold text-foreground">Manage Students</h2>

        <div class="mt-6">
            <h3 class="text-lg font-medium text-foreground">Enroll New Student</h3>
            <form wire:submit="store" class="mt-4">
                <input type="hidden" wire:model="academicYearId">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">First Name</label>
                        <input type="text" wire:model.debounce.300ms="firstName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Last Name</label>
                        <input type="text" wire:model.debounce.300ms="lastName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Email</label>
                        <input type="email" wire:model.debounce.300ms="email" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Date of Birth</label>
                        <input type="date" wire:model.debounce.300ms="dateOfBirth" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-foreground mb-1">Address</label>
                    <input type="text" wire:model.debounce.300ms="address" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Parent/Guardian Name</label>
                        <input type="text" wire:model.debounce.300ms="parentName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Parent Contact</label>
                        <input type="text" wire:model.debounce.300ms="parentContact" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Enrollment Date</label>
                        <input type="date" wire:model.debounce.300ms="enrollmentDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                        <select wire:model.debounce.300ms="status" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="graduated">Graduated</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
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
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Academic Year</label>
                        <select wire:model.debounce.300ms="academicYearId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Academic Year</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90 transition-colors">Enroll Student</button>
                </div>
            </form>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-medium text-foreground">Enrolled Students</h3>
            <div class="mt-4 overflow-x-auto rounded-lg border border-border">
                <table class="w-full text-sm">
                    <thead class="border-b border-border bg-secondary/50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-foreground">Name</th>
                            <th class="px-4 py-3 text-left font-medium text-foreground">Email</th>
                            <th class="px-4 py-3 text-left font-medium text-foreground">Class Level</th>
                            <th class="px-4 py-3 text-left font-medium text-foreground">Stream</th>
                            <th class="px-4 py-3 text-left font-medium text-foreground">Academic Year</th>
                            <th class="px-4 py-3 text-left font-medium text-foreground">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-foreground">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse ($students as $student)
                            <tr>
                                <td class="px-4 py-3">{{ $student->first_name . ' ' . $student->last_name }}</td>
                                <td class="px-4 py-3">{{ $student->email }}</td>
                                <td class="px-4 py-3">{{ $student->classLevel ? $student->classLevel->name : 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $student->stream ? $student->stream->name : 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $student->academicYear ? $student->academicYear->name : 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    @if($student->status === 'active')
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">{{ ucfirst($student->status) }}</span>
                                    @elseif($student->status === 'inactive')
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">{{ ucfirst($student->status) }}</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-700">{{ ucfirst($student->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <button class="rounded-lg border border-border px-4 py-2 text-sm font-medium text-foreground hover:bg-secondary transition-colors" wire:click="editStudent({{ $student->id }})">Edit</button>
                                        <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors" wire:click="deleteStudent({{ $student->id }})">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-muted-foreground">No students enrolled yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-medium text-foreground">Edit Student {{ $editStudentId ? '(ID: ' . $editStudentId . ')' : '' }}</h3>
            @if($editStudentId)
                <form wire:submit="update" class="mt-4">
                    <input type="hidden" name="editStudentId" value="{{ $editStudentId }}">

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">First Name</label>
                            <input type="text" wire:model.debounce.300ms="editFirstName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editFirstName }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Last Name</label>
                            <input type="text" wire:model.debounce.300ms="editLastName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editLastName }}">
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Email</label>
                            <input type="email" wire:model.debounce.300ms="editEmail" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editEmail }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Date of Birth</label>
                            <input type="date" wire:model.debounce.300ms="editDateOfBirth" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" value="{{ $editDateOfBirth }}">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-foreground mb-1">Address</label>
                        <input type="text" wire:model.debounce.300ms="editAddress" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" value="{{ $editAddress }}">
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Parent/Guardian Name</label>
                            <input type="text" wire:model.debounce.300ms="editParentName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editParentName }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Parent Contact</label>
                            <input type="text" wire:model.debounce.300ms="editParentContact" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editParentContact }}">
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Enrollment Date</label>
                            <input type="date" wire:model.debounce.300ms="editEnrollmentDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editEnrollmentDate }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                            <select wire:model.debounce.300ms="editStatus" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                                <option value="active" {{ $editStatus === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $editStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="graduated" {{ $editStatus === 'graduated' ? 'selected' : '' }}>Graduated</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
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
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Academic Year</label>
                            <select wire:model.debounce.300ms="editAcademicYearId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                                <option value="">Select Academic Year</option>
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ $year->id == $editAcademicYearId ? 'selected' : '' }}>{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90 transition-colors">Update Student</button>
                    </div>
                </form>
            @else
                <p class="mt-2 text-sm text-muted-foreground">Select a student to edit.</p>
            @endif
        </div>
    </div>
</div>
