<div class="space-y-6">
    <div class="rounded-xl border border-border bg-card p-6">
        <div class="border-b border-border pb-4 mb-6">
            <h4 class="text-lg font-semibold text-foreground">Manage Academic Years & Terms</h4>
        </div>

        <!-- Add Academic Year -->
        <div class="mb-6">
            <h5 class="text-base font-medium text-foreground mb-4">Add Academic Year</h5>
            <form wire:submit="createAcademicYear">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Name</label>
                        <input type="text" wire:model.debounce.300ms="academicYearName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Start Date</label>
                        <input type="date" wire:model.debounce.300ms="academicYearStartDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">End Date</label>
                        <input type="date" wire:model.debounce.300ms="academicYearEndDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                            <input type="checkbox" wire:model="isCurrent" class="h-4 w-4 rounded border-input text-chalkboard focus:ring-chalkboard">
                            Is Current
                        </label>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Create Academic Year</button>
                </div>
            </form>
        </div>

        <!-- Academic Years Table -->
        <h5 class="text-base font-medium text-foreground mb-4">Academic Years</h5>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Name</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Start Date</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">End Date</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Is Current</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($academicYears as $year)
                    <tr>
                        <td class="px-4 py-3">{{ $year->name }}</td>
                        <td class="px-4 py-3">{{ $year->start_date }}</td>
                        <td class="px-4 py-3">{{ $year->end_date }}</td>
                        <td class="px-4 py-3">{{ $year->is_current ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="updateAcademicYear({{ $year->id }})">Edit</button>
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="deleteAcademicYear({{ $year->id }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Add Term -->
        <div class="mt-6">
            <h5 class="text-base font-medium text-foreground mb-4">Add Term</h5>
            <form wire:submit="createTerm">
                <input type="hidden" name="academic_year_id" value="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Term Name</label>
                        <input type="text" wire:model.debounce.300ms="termName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Start Date</label>
                        <input type="date" wire:model.debounce.300ms="termStartDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">End Date</label>
                        <input type="date" wire:model.debounce.300ms="termEndDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                            <input type="checkbox" wire:model="isCurrentTerm" class="h-4 w-4 rounded border-input text-chalkboard focus:ring-chalkboard">
                            Is Current
                        </label>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Create Term</button>
                </div>
            </form>
        </div>

        <!-- Terms Table -->
        <h5 class="text-base font-medium text-foreground mt-6 mb-4">Terms</h5>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Academic Year</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Term Name</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Start Date</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">End Date</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Is Current</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($terms as $term)
                    <tr>
                        <td class="px-4 py-3">{{ $term->academicYear->name }}</td>
                        <td class="px-4 py-3">{{ $term->name }}</td>
                        <td class="px-4 py-3">{{ $term->start_date }}</td>
                        <td class="px-4 py-3">{{ $term->end_date }}</td>
                        <td class="px-4 py-3">{{ $term->is_current ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="updateTerm({{ $term->id }})">Edit</button>
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="deleteTerm({{ $term->id }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
