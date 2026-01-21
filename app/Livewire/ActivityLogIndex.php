<?php
namespace App\Livewire;
use Livewire\Component;

class ActivityLogIndex extends Component
{
    public function render()
    {
        return view('livewire.activity-log-index')->layout('layouts.app');
    }
}