<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Leave;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveSubmitted;
use App\Models\User;
use App\Mail\LeaveStatusUpdated;
use Illuminate\Support\Facades\Log;

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

        $leave = Leave::create([
            'user_id'    => auth()->id(),
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'reason'     => $this->reason,
            'status'     => 'pending'
        ]);

        // 2. LOGIC KIRIM EMAIL (Cari Admin & Kirim)
        $recipients = User::whereIn('role', ['administrator', 'supervisor'])
                          ->pluck('email')
                          ->toArray();
        
        if (!empty($recipients)) {
            try {
                Mail::to($recipients)->send(new LeaveSubmitted($leave));
            } catch (\Exception $e) {
                Log::error("Gagal kirim email submit: " . $e->getMessage());
            }
        }

        $this->reset(['start_date', 'end_date', 'reason']);
        session()->flash('message', 'Leave request submitted successfully!');
    }

    // === FITUR ADMIN (Approval) ===
    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'approved']);

        // LOGIC BARU: Kirim Email ke Karyawan yang mengajukan
        if ($leave->user->email) {
            // Coba kirim email
            try {
                Mail::to($leave->user->email)->send(new LeaveStatusUpdated($leave));
                Log::info("✅ Email Approve berhasil dikirim ke: " . $leave->user->email);
                
            } catch (\Exception $e) {
                Log::error("❌ Gagal kirim email approve: " . $e->getMessage());
            }
        }


        session()->flash('message', 'Leave request APPROVED.');
    }

    public function reject($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'rejected']);
        
        if ($leave->user->email) {
            try {
                Mail::to($leave->user->email)->send(new LeaveStatusUpdated($leave));
                Log::info("✅ Email REJECTED terkirim ke: " . $leave->user->email);
            } catch (\Exception $e) {
                Log::error("❌ Gagal kirim email reject: " . $e->getMessage());
            }
        }

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
                ->where('user_id', '!=', $user->id)
                ->latest()
                ->get();
        }

        return view('livewire.leave-index', [
            'leaves' => $myLeaves,
            'incomingRequests' => $incomingRequests
        ])->layout('layouts.app');
        
    }
}
