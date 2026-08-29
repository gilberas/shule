<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Timetable;
use App\Models\ClassLevel;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AcademicYear;
use App\Models\Term;

class ManageTimetable extends Component
{
    public $classLevelId;
    public $streamId;
    public $subjectId;
    public $teacherId;
    public $dayOfWeek;
    public $period;
    public $room;
    public $academicYearId;
    public $termId;

    public $editTimetableId;
    public $editClassLevelId;
    public $editStreamId;
    public $editSubjectId;
    public $editTeacherId;
    public $editDayOfWeek;
    public $editPeriod;
    public $editRoom;
    public $editAcademicYearId;
    public $editTermId;

    public function render()
    {
        return view('livewire.manage-timetable', [
            'timetables' => Timetable::with(['classLevel', 'stream', 'subject', 'teacher', 'academicYear', 'term'])->get(),
            'classLevels' => ClassLevel::orderBy('display_order')->get(),
            'streams' => Stream::with('classLevel')->get(),
            'subjects' => Subject::all(),
            'teachers' => Teacher::with('subject')->get(),
            'academicYears' => AcademicYear::orderBy('display_order')->get(),
            'terms' => Term::with('academicYear')->get(),
        ]);
    }

    public function store()
    {
        Timetable::create([
            'class_level_id' => $this->classLevelId,
            'stream_id' => $this->streamId,
            'subject_id' => $this->subjectId,
            'teacher_id' => $this->teacherId,
            'day_of_week' => $this->dayOfWeek,
            'period' => $this->period,
            'room' => $this->room,
            'academic_year_id' => $this->academicYearId,
            'term_id' => $this->termId,
        ]);

        $this->reset(['classLevelId', 'streamId', 'subjectId', 'teacherId', 'dayOfWeek', 'period', 'room', 'academicYearId', 'termId']);
        $this->emit('alert', 'Timetable slot added successfully!');
    }

    public function editTimetable($id)
    {
        $timetable = Timetable::find($id);
        $this->editTimetableId = $timetable->id;
        $this->editClassLevelId = $timetable->class_level_id;
        $this->editStreamId = $timetable->stream_id;
        $this->editSubjectId = $timetable->subject_id;
        $this->editTeacherId = $timetable->teacher_id;
        $this->editDayOfWeek = $timetable->day_of_week;
        $this->editPeriod = $timetable->period;
        $this->editRoom = $timetable->room;
        $this->editAcademicYearId = $timetable->academic_year_id;
        $this->editTermId = $timetable->term_id;
    }

    public function update()
    {
        $timetable = Timetable::find($this->editTimetableId);
        $timetable->update([
            'class_level_id' => $this->editClassLevelId,
            'stream_id' => $this->editStreamId,
            'subject_id' => $this->editSubjectId,
            'teacher_id' => $this->editTeacherId,
            'day_of_week' => $this->editDayOfWeek,
            'period' => $this->editPeriod,
            'room' => $this->editRoom,
            'academic_year_id' => $this->editAcademicYearId,
            'term_id' => $this->editTermId,
        ]);

        $this->resetInput();
        $this->emit('alert', 'Timetable slot updated successfully!');
    }

    public function deleteTimetable($id)
    {
        Timetable::find($id)->delete();
        $this->emit('alert', 'Timetable slot deleted successfully!');
    }

    public function resetInput()
    {
        $this->editTimetableId = null;
        $this->editClassLevelId = null;
        $this->editStreamId = null;
        $this->editSubjectId = null;
        $this->editTeacherId = null;
        $this->editDayOfWeek = '';
        $this->editPeriod = null;
        $this->editRoom = '';
        $this->editAcademicYearId = null;
        $this->editTermId = null;
    }
}