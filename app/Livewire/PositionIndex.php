<?php

namespace App\Livewire;

use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;

class PositionIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $positionId = null;

    // Form Fields
    public $name, $basic_salary;

    protected $rules = [
        'name' => 'required|min:3',
        'basic_salary' => 'required|numeric|min:0',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->positionId = null;
        $this->name = '';
        $this->basic_salary = '';
        $this->resetValidation();
    }

    public function edit($id)
    {
        $position = Position::findOrFail($id);
        $this->positionId = $id;
        $this->name = $position->name;
        $this->basic_salary = $position->basic_salary;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'basic_salary' => $this->basic_salary,
        ];

        if ($this->positionId) {
            Position::find($this->positionId)->update($data);
            $action = 'Updated';
        } else {
            Position::create($data);
            $action = 'Created';
        }

        // Catat Log
        if(class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => $action . ' Position',
                'details' => $action . ' position: ' . $this->name,
                'ip_address' => request()->ip()
            ]);
        }

        $this->closeModal();
        session()->flash('message', 'Position ' . $action . ' successfully.');
    }

    public function delete($id)
    {
        $position = Position::find($id);
        if ($position) {
            $position->delete();
            session()->flash('message', 'Position deleted successfully.');
        }
    }

    public function render()
    {
        $positions = Position::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.position-index', [
            'positions' => $positions
        ])->layout('layouts.app');
    }
}