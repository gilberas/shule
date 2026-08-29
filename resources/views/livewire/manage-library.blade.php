<div class="space-y-6">
    <div class="rounded-xl border border-border bg-card p-6">
        <div class="border-b border-border pb-4 mb-6">
            <h4 class="text-lg font-semibold text-foreground">Library Management</h4>
        </div>

        <!-- Add Book Form -->
        <div class="mb-6">
            <h5 class="text-base font-medium text-foreground mb-4">Add New Book</h5>
            <form wire:submit="storeBook">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Title</label>
                        <input type="text" wire:model.debounce.300ms="title" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Author</label>
                        <input type="text" wire:model.debounce.300ms="author" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">ISBN</label>
                        <input type="text" wire:model.debounce.300ms="isbn" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Category</label>
                        <input type="text" wire:model.debounce.300ms="category" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Publisher</label>
                        <input type="text" wire:model.debounce.300ms="publisher" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Published Year</label>
                        <input type="number" wire:model.debounce.300ms="publishedYear" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1000" max="2029">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Total Copies</label>
                        <input type="number" wire:model.debounce.300ms="totalCopies" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" required value="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Available Copies</label>
                        <input type="number" wire:model.debounce.300ms="availableCopies" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="0" required value="1">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-foreground mb-1">Description</label>
                    <textarea wire:model.debounce.300ms="description" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" rows="3"></textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Add Book</button>
                </div>
            </form>
        </div>

        <!-- Books Table -->
        <h5 class="text-base font-medium text-foreground mb-4">Book Catalog</h5>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Title</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Author</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">ISBN</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Category</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Total Copies</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Available</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Issuances</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($books as $book)
                    <tr>
                        <td class="px-4 py-3">{{ $book->title }}</td>
                        <td class="px-4 py-3">{{ $book->author }}</td>
                        <td class="px-4 py-3">{{ $book->isbn }}</td>
                        <td class="px-4 py-3">{{ $book->category }}</td>
                        <td class="px-4 py-3">{{ $book->total_copies }}</td>
                        <td class="px-4 py-3">{{ $book->available_copies }}</td>
                        <td class="px-4 py-3">{{ $book->issuances->count() }}</td>
                        <td class="px-4 py-3">
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="editBook({{ $book->id }})">Edit</button>
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="deleteBook({{ $book->id }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Edit Book Form -->
        <div class="mt-6">
            <h5 class="text-base font-medium text-foreground mb-4">Edit Book {{ $editBookId ? '(ID: ' . $editBookId . ')' : '' }}</h5>
            @if($editBookId)
            <form wire:submit="updateBook">
                <input type="hidden" name="editBookId" value="{{ $editBookId }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Title</label>
                        <input type="text" wire:model.debounce.300ms="editTitle" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editTitle }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Author</label>
                        <input type="text" wire:model.debounce.300ms="editAuthor" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editAuthor }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">ISBN</label>
                        <input type="text" wire:model.debounce.300ms="editIsbn" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editIsbn }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Category</label>
                        <input type="text" wire:model.debounce.300ms="editCategory" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editCategory }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Publisher</label>
                        <input type="text" wire:model.debounce.300ms="editPublisher" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" value="{{ $editPublisher }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Published Year</label>
                        <input type="number" wire:model.debounce.300ms="editPublishedYear" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1000" max="2029" value="{{ $editPublishedYear }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Total Copies</label>
                        <input type="number" wire:model.debounce.300ms="editTotalCopies" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" required value="{{ $editTotalCopies }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Available Copies</label>
                        <input type="number" wire:model.debounce.300ms="editAvailableCopies" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="0" required value="{{ $editAvailableCopies }}">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-foreground mb-1">Description</label>
                    <textarea wire:model.debounce.300ms="editDescription" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" rows="3">{{ $editDescription }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Update Book</button>
                </div>
            </form>
            @endif
        </div>

        <!-- Issue/Return Book Section -->
        <div class="mt-6">
            <h5 class="text-base font-medium text-foreground mb-4">Issue/Return Book</h5>
            <form wire:submit="issueBook">
                <input type="hidden" name="issueMode">

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
                        <label class="block text-sm font-medium text-foreground mb-1">Issue Date</label>
                        <input type="date" wire:model.debounce.300ms="issueDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Due Date</label>
                        <input type="date" wire:model.debounce.300ms="dueDate" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                        <select wire:model.debounce.300ms="status" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="issued" {{ $status === 'issued' ? 'selected' : '' }}>Issued</option>
                            <option value="returned" {{ $status === 'returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Notes</label>
                        <textarea wire:model.debounce.300ms="notes" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" rows="2"></textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">{{ $issueMode === 'return' ? 'Return Book' : 'Issue Book' }}</button>
                </div>
            </form>

            @if($issueMode === 'return')
            <form wire:submit="returnBook" class="mt-4">
                <input type="hidden" name="issuanceId">
                <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Mark as Returned</button>
            </form>
            @endif
        </div>
    </div>
</div>
