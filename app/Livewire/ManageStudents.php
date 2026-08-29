<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;
use App\Models\ClassLevel;
use App\Models\Stream;
use App\Models\AcademicYear;

class ManageStudents extends Component
{
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $dateOfBirth;
    public $address;
    public $parentName;
    public $parentContact;
    public $enrollmentDate;
    public $status = 'active';

    public $classLevelId;
    public $streamId;
    public $academicYearId;

    public $editStudentId;
    public $editFirstName = '';
    public $editLastName = '';
    public $editEmail = '';
    public $editDateOfBirth;
    public $editAddress;
    public $editParentName;
    public $editParentContact;
    public $editEnrollmentDate;
    public $editStatus = 'active';
    public $editClassLevelId;
    public $editStreamId;
    public $editAcademicYearId;

    public function render()
    {
        return view('livewire.manage-students', [
            'students' => Student::with(['classLevel', 'stream', 'academicYear'])->get(),
            'classLevels' => ClassLevel::orderBy('display_order')->get(),
            'streams' => Stream::with('classLevel')->get(),
            'academicYears' => AcademicYear::orderBy('display_order')->get(),
        ]);
    }

    public function store()
    {
        Student::create([
            'class_level_id' => $this->classLevelId,
            'stream_id' => $this->streamId,
            'academic_year_id' => $this->academicYearId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'date_of_birth' => $this->dateOfBirth,
            'address' => $this->address,
            'parent_name' => $this->parentName,
            'parent_contact' => $this->parentContact,
            'enrollment_date' => $this->enrollmentDate,
            'status' => $this->status,
        ]);

        $this->reset(['firstName', 'lastName', 'email', 'dateOfBirth', 'address', 'parentName', 'parentContact', 'enrollmentDate', 'status', 'classLevelId', 'streamId', 'academicYearId']);
        $this->emit('alert', 'Student enrolled successfully!');
    }

    public function editStudent($id)
    {
        $student = Student::find($id);
        $this->editStudentId = $student->id;
        $this->editFirstName = $student->first_name;
        $this->editLastName = $student->last_name;
        $this->editEmail = $student->email;
        $this->editDateOfBirth = $student->date_of_birth;
        $this->editAddress = $student->address;
        $this->editParentName = $student->parent_name;
        $this->editParentContact = $student->parent_contact;
        $this->editEnrollmentDate = $student->enrollment_date;
        $this->editStatus = $student->status;
        $this->editClassLevelId = $student->class_level_id;
        $this->editStreamId = $student->stream_id;
        $this->editAcademicYearId = $student->academic_year_id;
    }

    public function update()
    {
        $student = Student::find($this->editStudentId);
        $student->update([
            'class_level_id' => $this->editClassLevelId,
            'stream_id' => $this->editStreamId,
            'academic_year_id' => $this->editAcademicYearId,
            'first_name' => $this->editFirstName,
            'last_name' => $this->editLastName,
            'email' => $this->editEmail,
            'date_of_birth' => $this->editDateOfBirth,
            'address' => $this->editAddress,
            'parent_name' => $this->editParentName,
            'parent_contact' => $this->editParentContact,
            'enrollment_date' => $this->editEnrollmentDate,
            'status' => $this->editStatus,
        ]);

        $this->resetInput();
        $this->emit('alert', 'Student updated successfully!');
    }

    public function deleteStudent($id)
    {
        Student::find($id)->delete();
        $this->emit('alert', 'Student deleted successfully!');
    }

    public function resetInput()
    {
        $this->editStudentId = null;
        $this->editFirstName = '';
        $this->editLastName = '';
        $this->editEmail = '';
        $this->editDateOfBirth = null;
        $this->editAddress = '';
        $this->editParentName = '';
        $this->editParentContact = '';
        $this->editEnrollmentDate = null;
        $this->editStatus = 'active';
        $this->editClassLevelId = null;
        $this->editStreamId = null;
        $this->editAcademicYearId = null;
    }
}