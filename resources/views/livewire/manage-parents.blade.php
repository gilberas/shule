<div class="space-y-6">
    <div class="rounded-xl border border-border bg-card p-6">
        <h2 class="text-lg font-semibold text-foreground mb-4">Manage Parents/Guardians</h2>

        <div class="mb-6">
            <h3 class="text-sm font-medium text-foreground mb-3">Add New Parent</h3>
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
                        <label class="block text-sm font-medium text-foreground mb-1">Occupation</label>
                        <input type="text" wire:model.debounce.300ms="occupation" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Add Parent</button>
                </div>
            </form>
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-medium text-foreground mb-3">Registered Parents</h3>
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Phone</th>
                        <th class="px-4 py-3 text-left">Students</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($parents as $parent)
                    <tr>
                        <td class="px-4 py-3">{{ $parent->first_name . ' ' . $parent->last_name }}</td>
                        <td class="px-4 py-3">{{ $parent->email }}</td>
                        <td class="px-4 py-3">{{ $parent->phone }}</td>
                        <td class="px-4 py-3">{{ $parent->students->count() }}</td>
                        <td class="px-4 py-3">
                            <button class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800" wire:click="editParent({{ $parent->id }})">Edit</button>
                            <button class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-red-100 text-red-800" wire:click="deleteParent({{ $parent->id }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-medium text-foreground mb-3">Edit Parent {{ $editParentId ? '(ID: ' . $editParentId . ')' : '' }}</h3>
            @if($editParentId)
            <form wire:submit="update">
                <input type="hidden" name="editParentId" value="{{ $editParentId }}">

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
                        <label class="block text-sm font-medium text-foreground mb-1">Occupation</label>
                        <input type="text" wire:model.debounce.300ms="editOccupation" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" value="{{ $editOccupation }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Update Parent</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
