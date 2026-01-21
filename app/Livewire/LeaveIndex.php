<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Leave;
use Livewire\WithPagination;

class LeaveIndex extends Component
{
    use WithPagination;
    public $start_date, $end_date, $reason;

    protected $rules = [
        'start_date' => 'required|date|after_or_equal:today',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'reason'     => 'required|string|min:5',
    ];

    public function submit()
    {
        $this->validate();

        Leave::create([
            'user_id'    => auth()->id(),
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'reason'     => $this->reason,
            'status'     => 'pending'
        ]);

        $this->reset(['start_date', 'end_date', 'reason']);
        session()->flash('message', 'Leave request submitted successfully! Waiting for approval.');
    }

    // === FITUR ADMIN (Approval) ===
    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'approved']);
        session()->flash('message', 'Leave request APPROVED.');
    }

    public function reject($id)
    {
        $leave = Leave::findOrFail($id);
        // Bisa tambah admin_note kalau mau, tapi ini simpel dulu
        $leave->update(['status' => 'rejected']); 
        session()->flash('error', 'Leave request REJECTED.');
    }

    public function render()
    {
        $user = auth()->user();

        $myLeaves = Leave::where('user_id', $user->id)->latest()->paginate(5);

        $incomingRequests = [];
        if ($user->role !== 'user') {
            $incomingRequests = Leave::with('user')
                ->where('status', 'pending')
                ->where('user_id', '!=', $user->id) // Jangan tampilkan cuti sendiri di approval
                ->latest()
                ->get();
        }

        $incomingRequests = [];
        if ($user->role !== 'user') {
            $incomingRequests = Leave::with('user')
                ->where('status', 'pending')
                ->where('user_id', '!=', $user->id) // Jangan tampilkan cuti sendiri di approval
                ->latest()
                ->get();
        }

        return view('livewire.leave-index', [
            'leaves' => $myLeaves,
            'incomingRequests' => $incomingRequests
        ])->layout('layouts.app');
        
    }
}
