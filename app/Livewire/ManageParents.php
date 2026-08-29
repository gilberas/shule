<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SchoolParent;
use App\Models\Student;

class ManageParents extends Component
{
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $phone;
    public $address;
    public $occupation;

    public $editParentId;
    public $editFirstName = '';
    public $editLastName = '';
    public $editEmail = '';
    public $editPhone;
    public $editAddress;
    public $editOccupation;

    public function render()
    {
        return view('livewire.manage-parents', [
            'parents' => SchoolParent::with('students')->get(),
        ]);
    }

    public function store()
    {
        SchoolParent::create([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'occupation' => $this->occupation,
        ]);

        $this->reset(['firstName', 'lastName', 'email', 'phone', 'address', 'occupation']);
        $this->emit('alert', 'Parent added successfully!');
    }

    public function editParent($id)
    {
        $parent = SchoolParent::find($id);
        $this->editParentId = $parent->id;
        $this->editFirstName = $parent->first_name;
        $this->editLastName = $parent->last_name;
        $this->editEmail = $parent->email;
        $this->editPhone = $parent->phone;
        $this->editAddress = $parent->address;
        $this->editOccupation = $parent->occupation;
    }

    public function update()
    {
        $parent = SchoolParent::find($this->editParentId);
        $parent->update([
            'first_name' => $this->editFirstName,
            'last_name' => $this->editLastName,
            'email' => $this->editEmail,
            'phone' => $this->editPhone,
            'address' => $this->editAddress,
            'occupation' => $this->editOccupation,
        ]);

        $this->resetInput();
        $this->emit('alert', 'Parent updated successfully!');
    }

    public function deleteParent($id)
    {
        SchoolParent::find($id)->delete();
        $this->emit('alert', 'Parent deleted successfully!');
    }

    public function resetInput()
    {
        $this->editParentId = null;
        $this->editFirstName = '';
        $this->editLastName = '';
        $this->editEmail = '';
        $this->editPhone = null;
        $this->editAddress = '';
        $this->editOccupation = '';
    }
}
