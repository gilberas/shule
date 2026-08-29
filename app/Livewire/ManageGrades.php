<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ClassLevel;
use App\Models\Term;

class ManageGrades extends Component
{
    public $studentId;
    public $subjectId;
    public $classLevelId;
    public $termId;
    public $score = 0;
    public $gradeLetter = 'A';
    public $examType = 'Midterm';

    public $editGradeId;
    public $editStudentId;
    public $editSubjectId;
    public $editClassLevelId;
    public $editTermId;
    public $editScore = 0;
    public $editGradeLetter = 'A';
    public $editExamType = 'Midterm';

    public function render()
    {
        return view('livewire.manage-grades', [
            'grades' => Grade::with(['student', 'subject', 'term', 'classLevel'])->get(),
            'students' => Student::with(['classLevel', 'stream', 'academicYear'])->get(),
            'subjects' => Subject::all(),
            'classLevels' => ClassLevel::orderBy('display_order')->get(),
            'terms' => Term::with('academicYear')->get(),
        ]);
    }

    public function store()
    {
        Grade::create([
            'student_id' => $this->studentId,
            'subject_id' => $this->subjectId,
            'class_level_id' => $this->classLevelId,
            'term_id' => $this->termId,
            'score' => $this->score,
            'grade_letter' => $this->gradeLetter,
            'exam_type' => $this->examType,
        ]);

        $this->reset(['studentId', 'subjectId', 'classLevelId', 'termId', 'score', 'gradeLetter', 'examType']);
        $this->emit('alert', 'Grade recorded successfully!');
    }

    public function editGrade($id)
    {
        $grade = Grade::find($id);
        $this->editGradeId = $grade->id;
        $this->editStudentId = $grade->student_id;
        $this->editSubjectId = $grade->subject_id;
        $this->editClassLevelId = $grade->class_level_id;
        $this->editTermId = $grade->term_id;
        $this->editScore = $grade->score;
        $this->editGradeLetter = $grade->grade_letter;
        $this->editExamType = $grade->exam_type;
    }

    public function update()
    {
        $grade = Grade::find($this->editGradeId);
        $grade->update([
            'student_id' => $this->editStudentId,
            'subject_id' => $this->editSubjectId,
            'class_level_id' => $this->editClassLevelId,
            'term_id' => $this->editTermId,
            'score' => $this->editScore,
            'grade_letter' => $this->editGradeLetter,
            'exam_type' => $this->editExamType,
        ]);

        $this->resetInput();
        $this->emit('alert', 'Grade updated successfully!');
    }

    public function deleteGrade($id)
    {
        Grade::find($id)->delete();
        $this->emit('alert', 'Grade deleted successfully!');
    }

    public function resetInput()
    {
        $this->editGradeId = null;
        $this->editStudentId = null;
        $this->editSubjectId = null;
        $this->editClassLevelId = null;
        $this->editTermId = null;
        $this->editScore = 0;
        $this->editGradeLetter = 'A';
        $this->editExamType = 'Midterm';
    }
}