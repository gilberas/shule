<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ClassLevel;
use App\Models\Term;

class ManageAttendance extends Component
{
    public $studentId;
    public $subjectId;
    public $classLevelId;
    public $termId;
    public $attendanceDate;
    public $status = 'present';

    public $editAttendanceId;
    public $editStudentId;
    public $editSubjectId;
    public $editClassLevelId;
    public $editTermId;
    public $editAttendanceDate;
    public $editStatus = 'present';

    public function render()
    {
        return view('livewire.manage-attendance', [
            'attendances' => Attendance::with(['student', 'subject', 'term', 'classLevel'])->get(),
            'students' => Student::with(['classLevel', 'stream', 'academicYear'])->get(),
            'subjects' => Subject::all(),
            'classLevels' => ClassLevel::orderBy('display_order')->get(),
            'terms' => Term::with('academicYear')->get(),
        ]);
    }

    public function store()
    {
        Attendance::create([
            'student_id' => $this->studentId,
            'subject_id' => $this->subjectId,
            'class_level_id' => $this->classLevelId,
            'term_id' => $this->termId,
            'date' => $this->attendanceDate,
            'status' => $this->status,
        ]);

        $this->reset(['studentId', 'subjectId', 'classLevelId', 'termId', 'attendanceDate', 'status']);
        $this->emit('alert', 'Attendance recorded successfully!');
    }

    public function editAttendance($id)
    {
        $attendance = Attendance::find($id);
        $this->editAttendanceId = $attendance->id;
        $this->editStudentId = $attendance->student_id;
        $this->editSubjectId = $attendance->subject_id;
        $this->editClassLevelId = $attendance->class_level_id;
        $this->editTermId = $attendance->term_id;
        $this->editAttendanceDate = $attendance->date;
        $this->editStatus = $attendance->status;
    }

    public function update()
    {
        $attendance = Attendance::find($this->editAttendanceId);
        $attendance->update([
            'student_id' => $this->editStudentId,
            'subject_id' => $this->editSubjectId,
            'class_level_id' => $this->editClassLevelId,
            'term_id' => $this->editTermId,
            'date' => $this->editAttendanceDate,
            'status' => $this->editStatus,
        ]);

        $this->resetInput();
        $this->emit('alert', 'Attendance updated successfully!');
    }

    public function deleteAttendance($id)
    {
        Attendance::find($id)->delete();
        $this->emit('alert', 'Attendance record deleted successfully!');
    }

    public function resetInput()
    {
        $this->editAttendanceId = null;
        $this->editStudentId = null;
        $this->editSubjectId = null;
        $this->editClassLevelId = null;
        $this->editTermId = null;
        $this->editAttendanceDate = null;
        $this->editStatus = 'present';
    }
}