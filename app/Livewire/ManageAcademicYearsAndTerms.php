<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AcademicYear;
use App\Models\Term;

class ManageAcademicYearsAndTerms extends Component
{
    public $academicYearName = '';
    public $academicYearStartDate;
    public $academicYearEndDate;
    public $isCurrent = false;

    public $termName = '';
    public $termStartDate;
    public $termEndDate;
    public $isCurrentTerm = false;
    public $editTermId;

    public function render()
    {
        return view('livewire.manage-academic-yet-terms', [
            'academicYears' => AcademicYear::orderBy('order')->get(),
            'terms' => Term::with('academicYear')->get(),
        ]);
    }

    public function createAcademicYear()
    {
        AcademicYear::create([
            'name' => $this->academicYearName,
            'start_date' => $this->academicYearStartDate,
            'end_date' => $this->academicYearEndDate,
            'is_current' => $this->isCurrent,
        ]);
        $this->reset(['academicYearName', 'academicYearStartDate', 'academicYearEndDate', 'isCurrent']);
        $this->emit('alert', 'Academic year created successfully!');
    }

    public function updateAcademicYear($id)
    {
        $year = AcademicYear::find($id);
        $year->update([
            'name' => $this->academicYearName,
            'start_date' => $this->academicYearStartDate,
            'end_date' => $this->academicYearEndDate,
            'is_current' => $this->isCurrent,
        ]);
        $this->reset(['academicYearName', 'academicYearStartDate', 'academicYearEndDate', 'isCurrent']);
        $this->emit('alert', 'Academic year updated successfully!');
    }

    public function deleteAcademicYear($id)
    {
        AcademicYear::find($id)->delete();
        $this->emit('alert', 'Academic year deleted successfully!');
    }

    public function createTerm()
    {
        $academicYear = AcademicYear::findOrFail(request('academic_year_id'));
        Term::create([
            'academic_year_id' => request('academic_year_id'),
            'name' => $this->termName,
            'start_date' => $this->termStartDate,
            'end_date' => $this->termEndDate,
            'is_current' => $this->isCurrentTerm,
        ]);
        $this->reset(['termName', 'termStartDate', 'termEndDate', 'isCurrentTerm']);
        $this->emit('alert', 'Term created successfully!');
    }

    public function updateTerm($id)
    {
        $term = Term::find($id);
        $term->update([
            'academic_year_id' => request('academic_year_id'),
            'name' => request('term_name'),
            'start_date' => request('term_start_date'),
            'end_date' => request('term_end_date'),
            'is_current' => request('is_current_term'),
        ]);
        $this->emit('alert', 'Term updated successfully!');
    }

    public function deleteTerm($id)
    {
        Term::find($id)->delete();
        $this->emit('alert', 'Term deleted successfully!');
    }
}