<div class="space-y-6">
    <div class="rounded-xl border border-border bg-card p-6">
        <h2 class="text-lg font-semibold text-foreground mb-4">Exam Management</h2>

        <div class="mb-6">
            <h3 class="text-sm font-medium text-foreground mb-3">Add New Exam</h3>
            <form wire:submit="store">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Exam Name</label>
                        <input type="text" wire:model.debounce.300ms="examName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Description</label>
                        <textarea wire:model.debounce.300ms="examDescription" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" rows="3"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Total Marks</label>
                        <input type="number" wire:model.debounce.300ms="totalMarks" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" max="500" required value="{{ old('total_marks', 100) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Pass Marks</label>
                        <input type="number" wire:model.debounce.300ms="passMarks" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" max="499" required value="{{ old('pass_marks', 40) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Exam Date</label>
                        <input type="date" wire:model.debounce.300ms="examDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Create Exam</button>
                </div>
            </form>
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-medium text-foreground mb-3">Registered Exams</h3>
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left">Subject</th>
                        <th class="px-4 py-3 text-left">Class Level</th>
                        <th class="px-4 py-3 text-left">Term</th>
                        <th class="px-4 py-3 text-left">Exam Name</th>
                        <th class="px-4 py-3 text-left">Total Marks</th>
                        <th class="px-4 py-3 text-left">Pass Marks</th>
                        <th class="px-4 py-3 text-left">Exam Date</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($exams as $exam)
                    <tr>
                        <td class="px-4 py-3">{{ $exam->subject->name }}</td>
                        <td class="px-4 py-3">{{ $exam->classLevel->name }}</td>
                        <td class="px-4 py-3">{{ $exam->term->name }} ({{ $exam->term->academicYear->name }})</td>
                        <td class="px-4 py-3">{{ $exam->name }}</td>
                        <td class="px-4 py-3">{{ $exam->total_marks }}</td>
                        <td class="px-4 py-3">{{ $exam->pass_marks }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($exam->exam_date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <button class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800" wire:click="editExam({{ $exam->id }})">Edit</button>
                            <button class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-red-100 text-red-800" wire:click="deleteExam({{ $exam->id }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-medium text-foreground mb-3">Edit Exam {{ $editExamId ? '(ID: ' . $editExamId . ')' : '' }}</h3>
            @if($editExamId)
            <form wire:submit="update">
                <input type="hidden" name="editExamId" value="{{ $editExamId }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Exam Name</label>
                        <input type="text" wire:model.debounce.300ms="editExamName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editExamName }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Description</label>
                        <textarea wire:model.debounce.300ms="editExamDescription" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" rows="3">{{ $editExamDescription }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Total Marks</label>
                        <input type="number" wire:model.debounce.300ms="editTotalMarks" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" max="500" required value="{{ $editTotalMarks }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Pass Marks</label>
                        <input type="number" wire:model.debounce.300ms="editPassMarks" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" max="499" required value="{{ $editPassMarks }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Exam Date</label>
                        <input type="date" wire:model.debounce.300ms="editExamDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ \Carbon\Carbon::parse($editExamDate)->format('Y-m-d') }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Update Exam</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
