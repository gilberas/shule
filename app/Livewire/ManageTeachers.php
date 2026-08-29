<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Teacher;
use App\Models\Subject;

class ManageTeachers extends Component
{
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $phone;
    public $address;
    public $subjectId;
    public $hireDate;
    public $status = 'active';

    public $editTeacherId;
    public $editFirstName = '';
    public $editLastName = '';
    public $editEmail = '';
    public $editPhone;
    public $editAddress;
    public $editSubjectId;
    public $editHireDate;
    public $editStatus = 'active';

    public function render()
    {
        return view('livewire.manage-teachers', [
            'teachers' => Teacher::with('subject')->get(),
            'subjects' => Subject::all(),
        ]);
    }

    public function store()
    {
        Teacher::create([
            'subject_id' => $this->subjectId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'hire_date' => $this->hireDate,
            'status' => $this->status,
        ]);

        $this->reset(['firstName', 'lastName', 'email', 'phone', 'address', 'subjectId', 'hireDate', 'status']);
        $this->emit('alert', 'Teacher added successfully!');
    }

    public function editTeacher($id)
    {
        $teacher = Teacher::find($id);
        $this->editTeacherId = $teacher->id;
        $this->editFirstName = $teacher->first_name;
        $this->editLastName = $teacher->last_name;
        $this->editEmail = $teacher->email;
        $this->editPhone = $teacher->phone;
        $this->editAddress = $teacher->address;
        $this->editSubjectId = $teacher->subject_id;
        $this->editHireDate = $teacher->hire_date;
        $this->editStatus = $teacher->status;
    }

    public function update()
    {
        $teacher = Teacher::find($this->editTeacherId);
        $teacher->update([
            'subject_id' => $this->editSubjectId,
            'first_name' => $this->editFirstName,
            'last_name' => $this->editLastName,
            'email' => $this->editEmail,
            'phone' => $this->editPhone,
            'address' => $this->editAddress,
            'hire_date' => $this->editHireDate,
            'status' => $this->editStatus,
        ]);

        $this->resetInput();
        $this->emit('alert', 'Teacher updated successfully!');
    }

    public function deleteTeacher($id)
    {
        Teacher::find($id)->delete();
        $this->emit('alert', 'Teacher deleted successfully!');
    }

    public function resetInput()
    {
        $this->editTeacherId = null;
        $this->editFirstName = '';
        $this->editLastName = '';
        $this->editEmail = '';
        $this->editPhone = null;
        $this->editAddress = '';
        $this->editSubjectId = null;
        $this->editHireDate = null;
        $this->editStatus = 'active';
    }
}