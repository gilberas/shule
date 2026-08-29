<div class="space-y-6">
    <div class="rounded-xl border border-border bg-card p-6">
        <div class="border-b border-border pb-4 mb-6">
            <h4 class="text-lg font-semibold text-foreground">Hostel Management</h4>
        </div>

        <!-- Add Hostel Form -->
        <div class="mb-6">
            <h5 class="text-base font-medium text-foreground mb-4">Add New Hostel</h5>
            <form wire:submit="storeHostel">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Hostel Name</label>
                        <input type="text" wire:model.debounce.300ms="name" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Gender</label>
                        <select wire:model.debounce.300ms="gender" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="male" {{ $gender === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $gender === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="co-ed" {{ $gender === 'co-ed' ? 'selected' : '' }}>Co-Ed</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Description</label>
                        <textarea wire:model.debounce.300ms="description" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" rows="3"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Total Rooms</label>
                        <input type="number" wire:model.debounce.300ms="totalRooms" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" required value="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Total Capacity</label>
                        <input type="number" wire:model.debounce.300ms="totalCapacity" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" required value="50">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                        <select wire:model.debounce.300ms="status" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="full" {{ $status === 'full' ? 'selected' : '' }}>Full</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Add Hostel</button>
                </div>
            </form>
        </div>

        <!-- Hostels Table -->
        <h5 class="text-base font-medium text-foreground mb-4">Hostels</h5>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Name</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Gender</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Rooms/Capacity</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Occupancy</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($hostels as $hostel)
                    <tr>
                        <td class="px-4 py-3">{{ $hostel->name }}</td>
                        <td class="px-4 py-3">{{ ucfirst($hostel->gender) }}</td>
                        <td class="px-4 py-3">{{ $hostel->total_rooms }} rooms / {{ $hostel->total_capacity }} capacity</td>
                        <td class="px-4 py-3">{{ $hostel->roomAssignments->sum('current_occupants') }} / {{ $hostel->total_capacity }}</td>
                        <td class="px-4 py-3">
                            @if($hostel->status === 'active')
                            <span class="inline-flex items-center rounded-full bg-green-500/10 px-2 py-1 text-xs font-medium text-green-600">Active</span>
                            @elseif($hostel->status === 'inactive')
                            <span class="inline-flex items-center rounded-full bg-red-500/10 px-2 py-1 text-xs font-medium text-red-600">Inactive</span>
                            @else
                            <span class="inline-flex items-center rounded-full bg-yellow-500/10 px-2 py-1 text-xs font-medium text-yellow-600">Full</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="editHostel({{ $hostel->id }})">Edit</button>
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="deleteHostel({{ $hostel->id }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Edit Hostel Form -->
        <div class="mt-6">
            <h5 class="text-base font-medium text-foreground mb-4">Edit Hostel {{ $editHostelId ? '(ID: ' . $editHostelId . ')' : '' }}</h5>
            @if($editHostelId)
            <form wire:submit="updateHostel">
                <input type="hidden" name="editHostelId" value="{{ $editHostelId }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Hostel Name</label>
                        <input type="text" wire:model.debounce.300ms="editName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editName }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Gender</label>
                        <select wire:model.debounce.300ms="editGender" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="male" {{ $editGender === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $editGender === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="co-ed" {{ $editGender === 'co-ed' ? 'selected' : '' }}>Co-Ed</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Description</label>
                        <textarea wire:model.debounce.300ms="editDescription" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" rows="3">{{ $editDescription }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Total Rooms</label>
                        <input type="number" wire:model.debounce.300ms="editTotalRooms" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" required value="{{ $editTotalRooms }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Total Capacity</label>
                        <input type="number" wire:model.debounce.300ms="editTotalCapacity" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" required value="{{ $editTotalCapacity }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                        <select wire:model.debounce.300ms="editStatus" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                            <option value="active" {{ $editStatus === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $editStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="full" {{ $editStatus === 'full' ? 'selected' : '' }}>Full</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Update Hostel</button>
                </div>
            </form>
            @endif
        </div>

        <!-- Assign Student to Hostel Room -->
        <div class="mt-6">
            <h5 class="text-base font-medium text-foreground mb-4">Assign Student to Hostel Room</h5>
            <form wire:submit="assignStudentToRoom">
                <input type="hidden" name="assignMode">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Hostel</label>
                        <select wire:model.debounce.300ms="assignRoomId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Hostel</option>
                            @foreach ($hostels as $hostel)
                                <option value="{{ $hostel->id }}">{{ $hostel->name }} ({{ $hostel->total_rooms }} rooms)</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Room Number</label>
                        <input type="text" wire:model.debounce.300ms="assignRoomNumber" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Floor</label>
                        <input type="number" wire:model.debounce.300ms="assignFloor" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" required value="1">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                        <select wire:model.debounce.300ms="assignStatus" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="available" {{ $assignStatus === 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ $assignStatus === 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ $assignStatus === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">{{ $assignMode === 'assign' ? 'Assign Student' : 'Update Assignment' }}</button>
                </div>
            </form>

            @if($assignMode === 'update')
            <form wire:submit="updateAssignment" class="mt-4">
                <input type="hidden" name="editAssignmentId" value="{{ $editAssignmentId }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Hostel</label>
                        <select wire:model.debounce.300ms="editHostelIdAssignment" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="">Select Hostel</option>
                            @foreach ($hostels as $hostel)
                                <option value="{{ $hostel->id }}" {{ $hostel->id == $editHostelIdAssignment ? 'selected' : '' }}>{{ $hostel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Room Number</label>
                        <input type="text" wire:model.debounce.300ms="editRoomNumberAssignment" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editRoomNumberAssignment }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Floor</label>
                        <input type="number" wire:model.debounce.300ms="editFloorAssignment" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" required value="{{ $editFloorAssignment }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Current Occupants</label>
                        <input type="number" wire:model.debounce.300ms="editCurrentOccupants" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="0" required value="{{ $editCurrentOccupants }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                        <select wire:model.debounce.300ms="editStatusAssignment" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="available" {{ $editStatusAssignment === 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ $editStatusAssignment === 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ $editStatusAssignment === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90">Update Assignment</button>
                </div>
            </form>
            @endif
        </div>

        <!-- Room Assignments Table -->
        <h5 class="text-base font-medium text-foreground mt-6 mb-4">Hostel Room Assignments</h5>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-secondary/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Hostel</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Room</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Floor</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Capacity</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Occupants</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Student</th>
                        <th class="px-4 py-3 text-left font-medium text-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($roomAssignments as $assignment)
                    <tr>
                        <td class="px-4 py-3">{{ $assignment->hostel->name }}</td>
                        <td class="px-4 py-3">{{ $assignment->room_number }}</td>
                        <td class="px-4 py-3">{{ $assignment->floor }}</td>
                        <td class="px-4 py-3">{{ $assignment->capacity }}</td>
                        <td class="px-4 py-3">{{ $assignment->current_occupants }}</td>
                        <td class="px-4 py-3">
                            @if($assignment->status === 'available')
                            <span class="inline-flex items-center rounded-full bg-chalkboard/10 px-2 py-1 text-xs font-medium text-chalkboard">Available</span>
                            @elseif($assignment->status === 'occupied')
                            <span class="inline-flex items-center rounded-full bg-green-500/10 px-2 py-1 text-xs font-medium text-green-600">Occupied</span>
                            @else
                            <span class="inline-flex items-center rounded-full bg-yellow-500/10 px-2 py-1 text-xs font-medium text-yellow-600">Maintenance</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $assignment->student ? $assignment->student->first_name . ' ' . $assignment->student->last_name : 'Unassigned' }}</td>
                        <td class="px-4 py-3">
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="editAssignment({{ $assignment->id }})">Edit</button>
                            <button class="rounded-lg border border-border px-3 py-1.5 text-xs font-medium text-foreground hover:bg-secondary" wire:click="deleteAssignment({{ $assignment->id }})">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
