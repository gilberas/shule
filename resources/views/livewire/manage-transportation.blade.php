<div class="space-y-6">
    <div class="rounded-xl border border-border bg-card p-6">
        <h2 class="text-xl font-semibold text-foreground">Transportation Management</h2>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="space-y-6">
            <div class="rounded-xl border border-border bg-card p-6">
                <h3 class="mb-4 text-lg font-medium text-foreground">Add New Bus</h3>
                <form wire:submit="storeBus" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Plate Number</label>
                            <input type="text" wire:model.debounce.300ms="plateNumber" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Model</label>
                            <input type="text" wire:model.debounce.300ms="model" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Capacity</label>
                            <input type="number" wire:model.debounce.300ms="capacity" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                            <select wire:model.debounce.300ms="status" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="maintenance" {{ $status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90 transition-colors w-full">Add Bus</button>
                    </div>
                </form>
            </div>

            <div class="rounded-xl border border-border bg-card p-6">
                <h3 class="mb-4 text-lg font-medium text-foreground">Buses</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-border bg-secondary/50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Plate Number</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Model</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Capacity</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Status</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Assignments</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($buses as $bus)
                            <tr>
                                <td class="px-4 py-3">{{ $bus->plate_number }}</td>
                                <td class="px-4 py-3">{{ $bus->model }}</td>
                                <td class="px-4 py-3">{{ $bus->capacity }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $bus->status === 'active' ? 'bg-green-100 text-green-700' : ($bus->status === 'inactive' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($bus->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $bus->assignments->count() }}</td>
                                <td class="px-4 py-3">
                                    <button class="rounded-lg border border-border px-4 py-2 text-sm font-medium text-foreground hover:bg-secondary transition-colors" wire:click="editBus({{ $bus->id }})">Edit</button>
                                    <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors" wire:click="deleteBus({{ $bus->id }})">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($editBusId)
            <div class="rounded-xl border border-border bg-card p-6">
                <h3 class="mb-4 text-lg font-medium text-foreground">Edit Bus (ID: {{ $editBusId }})</h3>
                <form wire:submit="updateBus" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Plate Number</label>
                            <input type="text" wire:model.debounce.300ms="editPlateNumber" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editPlateNumber }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Model</label>
                            <input type="text" wire:model.debounce.300ms="editModel" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" value="{{ $editModel }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Capacity</label>
                            <input type="number" wire:model.debounce.300ms="editCapacity" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="1" required value="{{ $editCapacity }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                            <select wire:model.debounce.300ms="editStatus" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                                <option value="active" {{ $editStatus === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $editStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="maintenance" {{ $editStatus === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition-colors w-full">Update Bus</button>
                    </div>
                </form>
            </div>
            @endif

            <div class="rounded-xl border border-border bg-card p-6">
                <h3 class="mb-4 text-lg font-medium text-foreground">Add New Route</h3>
                <form wire:submit="storeRoute" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Route Name</label>
                            <input type="text" wire:model.debounce.300ms="routeName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Start Point</label>
                            <input type="text" wire:model.debounce.300ms="startPoint" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">End Point</label>
                            <input type="text" wire:model.debounce.300ms="endPoint" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Via Points</label>
                            <input type="text" wire:model.debounce.300ms="viaPoints" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" placeholder="Comma separated">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Distance (km)</label>
                            <input type="number" wire:model.debounce.300ms="distanceKm" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="0">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Travel Time (minutes)</label>
                            <input type="number" wire:model.debounce.300ms="travelTimeMinutes" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="0">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90 transition-colors w-full">Add Route</button>
                    </div>
                </form>
            </div>

            <div class="rounded-xl border border-border bg-card p-6">
                <h3 class="mb-4 text-lg font-medium text-foreground">Routes</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-border bg-secondary/50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Name</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Start Point</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">End Point</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Distance</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Travel Time</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Status</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($routes as $route)
                            <tr>
                                <td class="px-4 py-3">{{ $route->name }}</td>
                                <td class="px-4 py-3">{{ $route->start_point }}</td>
                                <td class="px-4 py-3">{{ $route->end_point }}</td>
                                <td class="px-4 py-3">{{ $route->distance_km ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $route->travel_time_minutes ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $route->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($route->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <button class="rounded-lg border border-border px-4 py-2 text-sm font-medium text-foreground hover:bg-secondary transition-colors" wire:click="editRoute({{ $route->id }})">Edit</button>
                                    <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors" wire:click="deleteRoute({{ $route->id }})">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($editRouteId)
            <div class="rounded-xl border border-border bg-card p-6">
                <h3 class="mb-4 text-lg font-medium text-foreground">Edit Route (ID: {{ $editRouteId }})</h3>
                <form wire:submit="updateRoute" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Route Name</label>
                            <input type="text" wire:model.debounce.300ms="editRouteName" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editRouteName }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Start Point</label>
                            <input type="text" wire:model.debounce.300ms="editStartPoint" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editStartPoint }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">End Point</label>
                            <input type="text" wire:model.debounce.300ms="editEndPoint" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editEndPoint }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Via Points</label>
                            <input type="text" wire:model.debounce.300ms="editViaPoints" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" value="{{ $editViaPoints }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Distance (km)</label>
                            <input type="number" wire:model.debounce.300ms="editDistanceKm" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="0" value="{{ $editDistanceKm }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Travel Time (minutes)</label>
                            <input type="number" wire:model.debounce.300ms="editTravelTimeMinutes" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" min="0" value="{{ $editTravelTimeMinutes }}">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition-colors w-full">Update Route</button>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="rounded-xl border border-border bg-card p-6">
                <h3 class="mb-4 text-lg font-medium text-foreground">Assign Student Transportation</h3>
                <form wire:submit="assignStudent" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
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
                            <label class="block text-sm font-medium text-foreground mb-1">Bus</label>
                            <select wire:model.debounce.300ms="assignBusId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                                <option value="">Select Bus</option>
                                @foreach ($buses as $bus)
                                    <option value="{{ $bus->id }}">{{ $bus->plate_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Route</label>
                            <select wire:model.debounce.300ms="assignRouteId" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                                <option value="">Select Route</option>
                                @foreach ($routes as $route)
                                    <option value="{{ $route->id }}">{{ $route->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Pickup Point</label>
                            <input type="text" wire:model.debounce.300ms="pickupPoint" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Drop-off Point</label>
                            <input type="text" wire:model.debounce.300ms="dropOffPoint" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                            <select wire:model.debounce.300ms="assignStatus" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                                <option value="assigned" {{ $assignStatus === 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="active" {{ $assignStatus === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ $assignStatus === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $assignStatus === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="rounded-lg bg-chalkboard px-4 py-2 text-sm font-medium text-cream hover:bg-chalkboard/90 transition-colors w-full">{{ $assignMode === 'assign' ? 'Assign Student' : 'Update Assignment' }}</button>
                    </div>
                </form>
            </div>

            @if($assignMode === 'update')
            <div class="rounded-xl border border-border bg-card p-6">
                <h3 class="mb-4 text-lg font-medium text-foreground">Update Assignment</h3>
                <form wire:submit="updateAssignment" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
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
                            <label class="block text-sm font-medium text-foreground mb-1">Bus</label>
                            <select wire:model.debounce.300ms="editBusIdAssignment" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                                <option value="">Select Bus</option>
                                @foreach ($buses as $bus)
                                    <option value="{{ $bus->id }}" {{ $bus->id == $editBusIdAssignment ? 'selected' : '' }}>{{ $bus->plate_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Route</label>
                            <select wire:model.debounce.300ms="editRouteIdAssignment" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                                <option value="">Select Route</option>
                                @foreach ($routes as $route)
                                    <option value="{{ $route->id }}" {{ $route->id == $editRouteIdAssignment ? 'selected' : '' }}>{{ $route->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Pickup Point</label>
                            <input type="text" wire:model.debounce.300ms="editPickupPointAssignment" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editPickupPointAssignment }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Drop-off Point</label>
                            <input type="text" wire:model.debounce.300ms="editDropOffPointAssignment" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required value="{{ $editDropOffPointAssignment }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                            <select wire:model.debounce.300ms="editStatusAssignment" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" required>
                                <option value="assigned" {{ $editStatusAssignment === 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="active" {{ $editStatusAssignment === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ $editStatusAssignment === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $editStatusAssignment === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition-colors w-full">Update Assignment</button>
                    </div>
                </form>
            </div>
            @endif

            <div class="rounded-xl border border-border bg-card p-6">
                <h3 class="mb-4 text-lg font-medium text-foreground">Transportation Assignments</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-border bg-secondary/50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Student</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Bus</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Route</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Pickup</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Drop-off</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Status</th>
                                <th class="px-4 py-3 text-left font-medium text-muted-foreground">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($assignments as $assignment)
                            <tr>
                                <td class="px-4 py-3">{{ $assignment->student->first_name . ' ' . $assignment->student->last_name }}</td>
                                <td class="px-4 py-3">{{ $assignment->bus->plate_number }}</td>
                                <td class="px-4 py-3">{{ $assignment->route->name }}</td>
                                <td class="px-4 py-3">{{ $assignment->pickup_point }}</td>
                                <td class="px-4 py-3">{{ $assignment->drop_off_point }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $assignment->status === 'assigned' ? 'bg-blue-100 text-blue-700' : ($assignment->status === 'active' ? 'bg-green-100 text-green-700' : ($assignment->status === 'completed' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                                        {{ ucfirst($assignment->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <button class="rounded-lg border border-border px-4 py-2 text-sm font-medium text-foreground hover:bg-secondary transition-colors" wire:click="editAssignment({{ $assignment->id }})">Edit</button>
                                    <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors" wire:click="deleteAssignment({{ $assignment->id }})">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
