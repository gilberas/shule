<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Hostel;
use App\Models\HostelRoomAssignment;
use App\Models\Student;

class ManageHostel extends Component
{
    public $name = '';
    public $description;
    public $gender = 'male';
    public $totalRooms = 1;
    public $totalCapacity = 50;
    public $status = 'active';

    public $editHostelId;
    public $editName = '';
    public $editDescription;
    public $editGender = 'male';
    public $editTotalRooms = 1;
    public $editTotalCapacity = 50;
    public $editStatus = 'active';

    public $studentId;
    public $assignRoomId;
    public $assignRoomNumber;
    public $assignFloor = 1;
    public $assignStatus = 'available';

    public $editAssignmentId;
    public $editHostelIdAssignment;
    public $editRoomNumberAssignment;
    public $editFloorAssignment;
    public $editCurrentOccupants = 0;
    public $editStatusAssignment = 'available';

    public $assignMode = 'assign';

    public function render()
    {
        return view('livewire.manage-hostel', [
            'hostels' => Hostel::with('roomAssignments')->get(),
            'roomAssignments' => HostelRoomAssignment::with(['hostel', 'student'])->get(),
            'students' => Student::with(['classLevel', 'stream', 'academicYear'])->get(),
        ]);
    }

    public function storeHostel()
    {
        Hostel::create([
            'name' => $this->name,
            'description' => $this->description,
            'gender' => $this->gender,
            'total_rooms' => $this->totalRooms,
            'total_capacity' => $this->totalCapacity,
            'status' => $this->status,
        ]);

        $this->reset(['name', 'description', 'gender', 'totalRooms', 'totalCapacity', 'status']);
        $this->emit('alert', 'Hostel created successfully!');
    }

    public function editHostel($id)
    {
        $hostel = Hostel::find($id);
        $this->editHostelId = $hostel->id;
        $this->editName = $hostel->name;
        $this->editDescription = $hostel->description;
        $this->editGender = $hostel->gender;
        $this->editTotalRooms = $hostel->total_rooms;
        $this->editTotalCapacity = $hostel->total_capacity;
        $this->editStatus = $hostel->status;
    }

    public function updateHostel()
    {
        $hostel = Hostel::find($this->editHostelId);
        $hostel->update([
            'name' => $this->editName,
            'description' => $this->editDescription,
            'gender' => $this->editGender,
            'total_rooms' => $this->editTotalRooms,
            'total_capacity' => $this->editTotalCapacity,
            'status' => $this->editStatus,
        ]);

        $this->resetHostelInput();
        $this->emit('alert', 'Hostel updated successfully!');
    }

    public function deleteHostel($id)
    {
        Hostel::find($id)->delete();
        $this->emit('alert', 'Hostel deleted successfully!');
    }

    public function resetHostelInput()
    {
        $this->editHostelId = null;
        $this->editName = '';
        $this->editDescription = '';
        $this->editGender = 'male';
        $this->editTotalRooms = 1;
        $this->editTotalCapacity = 50;
        $this->editStatus = 'active';
    }

    public function assignStudentToRoom()
    {
        HostelRoomAssignment::create([
            'hostel_id' => $this->assignRoomId,
            'room_number' => $this->assignRoomNumber,
            'capacity' => $this->totalCapacity,  // or calculate based on hostel
            'current_occupants' => 1,
            'floor' => $this->assignFloor,
            'status' => $this->assignStatus,
        ]);

        $this->reset(['studentId', 'assignRoomId', 'assignRoomNumber', 'assignFloor', 'assignStatus']);
        $this->emit('alert', 'Student assigned to hostel room successfully!');
    }

    public function editAssignment($id)
    {
        $assignment = HostelRoomAssignment::find($id);
        $this->editAssignmentId = $assignment->id;
        $this->editHostelIdAssignment = $assignment->hostel_id;
        $this->editRoomNumberAssignment = $assignment->room_number;
        $this->editFloorAssignment = $assignment->floor;
        $this->editCurrentOccupants = $assignment->current_occupants;
        $this->editStatusAssignment = $assignment->status;
    }

    public function updateAssignment()
    {
        $assignment = HostelRoomAssignment::find($this->editAssignmentId);
        $assignment->update([
            'hostel_id' => $this->editHostelIdAssignment,
            'room_number' => $this->editRoomNumberAssignment,
            'current_occupants' => $this->editCurrentOccupants,
            'floor' => $this->editFloorAssignment,
            'status' => $this->editStatusAssignment,
        ]);

        $this->resetAssignmentInput();
        $this->emit('alert', 'Hostel room assignment updated successfully!');
    }

    public function resetAssignmentInput()
    {
        $this->editAssignmentId = null;
        $this->editHostelIdAssignment = null;
        $this->editRoomNumberAssignment = '';
        $this->editFloorAssignment = 1;
        $this->editCurrentOccupants = 0;
        $this->editStatusAssignment = 'available';
    }
}