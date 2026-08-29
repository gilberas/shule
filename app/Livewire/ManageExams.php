<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\ClassLevel;
use App\Models\Term;

class ManageExams extends Component
{
    public $subjectId;
    public $classLevelId;
    public $termId;
    public $examName;
    public $examDescription;
    public $totalMarks = 100;
    public $passMarks = 40;
    public $examDate;

    public $editExamId;
    public $editSubjectId;
    public $editClassLevelId;
    public $editTermId;
    public $editExamName;
    public $editExamDescription;
    public $editTotalMarks = 100;
    public $editPassMarks = 40;
    public $editExamDate;

    public function render()
    {
        return view('livewire.manage-exams', [
            'exams' => Exam::with(['subject', 'classLevel', 'term'])->get(),
            'subjects' => Subject::all(),
            'classLevels' => ClassLevel::orderBy('display_order')->get(),
            'terms' => Term::with('academicYear')->get(),
        ]);
    }

    public function store()
    {
        Exam::create([
            'subject_id' => $this->subjectId,
            'class_level_id' => $this->classLevelId,
            'term_id' => $this->termId,
            'name' => $this->examName,
            'description' => $this->examDescription,
            'total_marks' => $this->totalMarks,
            'pass_marks' => $this->passMarks,
            'exam_date' => $this->examDate,
        ]);

        $this->reset(['subjectId', 'classLevelId', 'termId', 'examName', 'examDescription', 'totalMarks', 'passMarks', 'examDate']);
        $this->emit('alert', 'Exam created successfully!');
    }

    public function editExam($id)
    {
        $exam = Exam::find($id);
        $this->editExamId = $exam->id;
        $this->editSubjectId = $exam->subject_id;
        $this->editClassLevelId = $exam->class_level_id;
        $this->editTermId = $exam->term_id;
        $this->editExamName = $exam->name;
        $this->editExamDescription = $exam->description;
        $this->editTotalMarks = $exam->total_marks;
        $this->editPassMarks = $exam->pass_marks;
        $this->editExamDate = $exam->exam_date;
    }

    public function update()
    {
        $exam = Exam::find($this->editExamId);
        $exam->update([
            'subject_id' => $this->editSubjectId,
            'class_level_id' => $this->editClassLevelId,
            'term_id' => $this->editTermId,
            'name' => $this->editExamName,
            'description' => $this->editExamDescription,
            'total_marks' => $this->editTotalMarks,
            'pass_marks' => $this->editPassMarks,
            'exam_date' => $this->editExamDate,
        ]);

        $this->resetInput();
        $this->emit('alert', 'Exam updated successfully!');
    }

    public function deleteExam($id)
    {
        Exam::find($id)->delete();
        $this->emit('alert', 'Exam deleted successfully!');
    }

    public function resetInput()
    {
        $this->editExamId = null;
        $this->editSubjectId = null;
        $this->editClassLevelId = null;
        $this->editTermId = null;
        $this->editExamName = '';
        $this->editExamDescription = '';
        $this->editTotalMarks = 100;
        $this->editPassMarks = 40;
        $this->editExamDate = null;
    }
}