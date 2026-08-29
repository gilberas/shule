<div class="space-y-6">
    <div class="rounded-xl border border-border bg-card p-6">
        <h2 class="text-lg font-semibold text-foreground mb-4">Record Student Grades</h2>

        <div class="mb-6">
            <h3 class="text-sm font-medium text-foreground mb-3">Record New Grade</h3>
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

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
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
                        <label class="block text-sm font-medium text-foreground mb-1">Score</label>
                        <input type="number" wire:model.debounce.300ms="score" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="0" max="100" required value="{{ old('score') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Grade Letter</label>
                        <input type="text" wire:model.debounce.300ms="gradeLetter" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" maxlength="2" required value="{{ old('grade_letter', 'A') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Exam Type</label>
                        <select wire:model.debounce.300ms="examType" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="Midterm">Midterm</option>
                            <option value="Final">Final</option>
                            <option value="Quiz">Quiz</option>
                            <option value="Assignment">Assignment</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Record Grade</button>
                </div>
            </form>
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-medium text-foreground mb-3">Student Grades</h3>
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left">Student</th>
                        <th class="px-4 py-3 text-left">Subject</th>
                        <th class="px-4 py-3 text-left">Class Level</th>
                        <th class="px-4 py-3 text-left">Term</th>
                        <th class="px-4 py-3 text-left">Score</th>
                        <th class="px-4 py-3 text-left">Grade</th>
                        <th class="px-4 py-3 text-left">Exam Type</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($grades as $grade)
                    <tr>
                        <td class="px-4 py-3">{{ $grade->student->first_name . ' ' . $grade->student->last_name }}</td>
                        <td class="px-4 py-3">{{ $grade->subject->name }}</td>
                        <td class="px-4 py-3">{{ $grade->classLevel->name }}</td>
                        <td class="px-4 py-3">{{ $grade->term->name }} ({{ $grade->term->academicYear->name }})</td>
                        <td class="px-4 py-3">{{ $grade->score }}</td>
                        <td class="px-4 py-3">{{ $grade->grade_letter }}</td>
                        <td class="px-4 py-3">{{ $grade->exam_type }}</td>
                        <td class="px-4 py-3">
                            <button class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800" wire:click="editGrade({{ $grade->id }})">Edit</button>
                            <button class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-red-100 text-red-800" wire:click="deleteGrade({{ $grade->id }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-medium text-foreground mb-3">Edit Grade {{ $editGradeId ? '(ID: ' . $editGradeId . ')' : '' }}</h3>
            @if($editGradeId)
            <form wire:submit="update">
                <input type="hidden" name="editGradeId" value="{{ $editGradeId }}">

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

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
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
                        <label class="block text-sm font-medium text-foreground mb-1">Score</label>
                        <input type="number" wire:model.debounce.300ms="editScore" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="0" max="100" required value="{{ $editScore }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Grade Letter</label>
                        <input type="text" wire:model.debounce.300ms="editGradeLetter" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" maxlength="2" required value="{{ $editGradeLetter }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Exam Type</label>
                        <select wire:model.debounce.300ms="editExamType" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="Midterm" {{ $editExamType === 'Midterm' ? 'selected' : '' }}>Midterm</option>
                            <option value="Final" {{ $editExamType === 'Final' ? 'selected' : '' }}>Final</option>
                            <option value="Quiz" {{ $editExamType === 'Quiz' ? 'selected' : '' }}>Quiz</option>
                            <option value="Assignment" {{ $editExamType === 'Assignment' ? 'selected' : '' }}>Assignment</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Update Grade</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
