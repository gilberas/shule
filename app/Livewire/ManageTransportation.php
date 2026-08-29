<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bus;
use App\Models\Route;
use App\Models\StudentBusAssignment;
use App\Models\Student;

class ManageTransportation extends Component
{
    public $plateNumber;
    public $model;
    public $capacity;
    public $status = 'active';

    public $routeId;
    public $pickupPoint;
    public $dropOffPoint;

    public $editBusId;
    public $editPlateNumber;
    public $editModel;
    public $editCapacity;
    public $editStatus = 'active';
    public $editRouteId;
    public $editPickupPoint;
    public $editDropOffPoint;

    public $studentId;
    public $assignRouteId;
    public $assignBusId;
    public $assignStatus = 'assigned';

    public $editAssignmentId;
    public $editStudentId;
    public $editBusIdAssignment;
    public $editRouteIdAssignment;
    public $editPickupPointAssignment;
    public $editDropOffPointAssignment;
    public $editStatusAssignment = 'assigned';

    public $assignMode = 'assign';

    public function render()
    {
        return view('livewire.manage-transportation', [
            'buses' => Bus::with('assignments')->get(),
            'routes' => Route::all(),
            'assignments' => StudentBusAssignment::with(['student', 'bus', 'route'])->get(),
            'students' => Student::with(['classLevel', 'stream', 'academicYear'])->get(),
        ]);
    }

    public function storeBus()
    {
        Bus::create([
            'plate_number' => $this->plateNumber,
            'model' => $this->model,
            'capacity' => $this->capacity,
            'status' => $this->status,
        ]);

        $this->reset(['plateNumber', 'model', 'capacity', 'status']);
        $this->emit('alert', 'Bus added successfully!');
    }

    public function editBus($id)
    {
        $bus = Bus::find($id);
        $this->editBusId = $bus->id;
        $this->editPlateNumber = $bus->plate_number;
        $this->editModel = $bus->model;
        $this->editCapacity = $bus->capacity;
        $this->editStatus = $bus->status;
    }

    public function updateBus()
    {
        $bus = Bus::find($this->editBusId);
        $bus->update([
            'plate_number' => $this->editPlateNumber,
            'model' => $this->editModel,
            'capacity' => $this->editCapacity,
            'status' => $this->editStatus,
        ]);

        $this->resetInput();
        $this->emit('alert', 'Bus updated successfully!');
    }

    public function deleteBus($id)
    {
        Bus::find($id)->delete();
        $this->emit('alert', 'Bus deleted successfully!');
    }

    public function resetInput()
    {
        $this->editBusId = null;
        $this->editPlateNumber = '';
        $this->editModel = '';
        $this->editCapacity = null;
        $this->editStatus = 'active';
    }

    public function storeRoute()
    {
        Route::create([
            'name' => $this->routeName,
            'start_point' => $this->startPoint,
            'end_point' => $this->endPoint,
            'via_points' => $this->viaPoints,
            'distance_km' => $this->distanceKm,
            'travel_time_minutes' => $this->travelTimeMinutes,
            'status' => 'active',
        ]);

        $this->reset(['routeName', 'startPoint', 'endPoint', 'viaPoints', 'distanceKm', 'travelTimeMinutes']);
        $this->emit('alert', 'Route added successfully!');
    }

    public function editRoute($id)
    {
        $route = Route::find($id);
        $this->editRouteId = $route->id;
        $this->editRouteName = $route->name;
        $this->editStartPoint = $route->start_point;
        $this->editEndPoint = $route->end_point;
        $this->editViaPoints = $route->via_points;
        $this->editDistanceKm = $route->distance_km;
        $this->editTravelTimeMinutes = $route->travel_time_minutes;
    }

    public function updateRoute()
    {
        $route = Route::find($this->editRouteId);
        $route->update([
            'name' => $this->editRouteName,
            'start_point' => $this->editStartPoint,
            'end_point' => $this->editEndPoint,
            'via_points' => $this->editViaPoints,
            'distance_km' => $this->editDistanceKm,
            'travel_time_minutes' => $this->editTravelTimeMinutes,
        ]);

        $this->resetInput();
        $this->emit('alert', 'Route updated successfully!');
    }

    public function deleteRoute($id)
    {
        Route::find($id)->delete();
        $this->emit('alert', 'Route deleted successfully!');
    }

    public function resetRouteInput()
    {
        $this->editRouteId = null;
        $this->editRouteName = '';
        $this->editStartPoint = '';
        $this->editEndPoint = '';
        $this->editViaPoints = '';
        $this->editDistanceKm = null;
        $this->editTravelTimeMinutes = null;
    }

    public function assignStudent()
    {
        StudentBusAssignment::create([
            'student_id' => $this->studentId,
            'bus_id' => $this->assignBusId,
            'route_id' => $this->assignRouteId,
            'pickup_point' => $this->pickupPoint,
            'drop_off_point' => $this->dropOffPoint,
            'status' => $this->assignStatus,
        ]);

        $this->reset(['studentId', 'assignBusId', 'assignRouteId', 'pickupPoint', 'dropOffPoint', 'assignStatus']);
        $this->emit('alert', 'Student assigned to transportation successfully!');
    }

    public function editAssignment($id)
    {
        $assignment = StudentBusAssignment::find($id);
        $this->editAssignmentId = $assignment->id;
        $this->editStudentId = $assignment->student_id;
        $this->editBusIdAssignment = $assignment->bus_id;
        $this->editRouteIdAssignment = $assignment->route_id;
        $this->editPickupPointAssignment = $assignment->pickup_point;
        $this->editDropOffPointAssignment = $assignment->drop_off_point;
        $this->editStatusAssignment = $assignment->status;
    }

    public function updateAssignment()
    {
        $assignment = StudentBusAssignment::find($this->editAssignmentId);
        $assignment->update([
            'student_id' => $this->editStudentId,
            'bus_id' => $this->editBusIdAssignment,
            'route_id' => $this->editRouteIdAssignment,
            'pickup_point' => $this->editPickupPointAssignment,
            'drop_off_point' => $this->editDropOffPointAssignment,
            'status' => $this->editStatusAssignment,
        ]);

        $this->resetAssignmentInput();
        $this->emit('alert', 'Transportation assignment updated successfully!');
    }

    public function deleteAssignment($id)
    {
        StudentBusAssignment::find($id)->delete();
        $this->emit('alert', 'Transportation assignment deleted successfully!');
    }

    public function resetAssignmentInput()
    {
        $this->editAssignmentId = null;
        $this->editStudentId = null;
        $this->editBusIdAssignment = null;
        $this->editRouteIdAssignment = null;
        $this->editPickupPointAssignment = '';
        $this->editDropOffPointAssignment = '';
        $this->editStatusAssignment = 'assigned';
    }
}