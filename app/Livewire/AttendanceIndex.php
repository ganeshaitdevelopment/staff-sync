<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceIndex extends Component
{
    use WithPagination;

    public $dateFilter;

    public function mount()
    {
        $this->dateFilter = Carbon::today()->format('Y-m-d');
    }

    // === 1. LOGIC CHECK IN (ABSEN MASUK) ===
    public function checkIn()
    {
        // Cek dulu hari ini udah absen belum?
        $exists = Attendance::where('user_id', auth()->id())
                    ->whereDate('date', Carbon::today())
                    ->exists();

        if ($exists) {
            session()->flash('error', 'You have already checked in today!');
            return;
        }

        // Simpan Data
        Attendance::create([
            'user_id' => auth()->id(),
            'date' => Carbon::today(),
            'check_in_time' => Carbon::now(), // Jam sekarang
            'status' => 'present',
        ]);

        session()->flash('message', 'Check In Successful! Have a great day.');
    }

    // === 2. LOGIC CHECK OUT (ABSEN PULANG) ===
    public function checkOut()
    {
        $attendance = Attendance::where('user_id', auth()->id())
                        ->whereDate('date', Carbon::today())
                        ->first();

        if (!$attendance) {
            session()->flash('error', 'Please check in first!');
            return;
        }

        if ($attendance->check_out_time) {
            session()->flash('error', 'You have already checked out today!');
            return;
        }

        // Update Jam Pulang
        $attendance->update([
            'check_out_time' => Carbon::now(),
        ]);

        session()->flash('message', 'Check Out Successful! See you tomorrow.');
    }

    public function render()
    {
        // Cek status absen user hari ini (untuk update tampilan tombol)
        $todayAttendance = Attendance::where('user_id', auth()->id())
                            ->whereDate('date', Carbon::today())
                            ->first();

        // Ambil data history
        $attendances = Attendance::with('user')
            ->whereDate('date', $this->dateFilter)
            ->latest('check_in_time')
            ->paginate(10);

        return view('livewire.attendance-index', [
            'attendances' => $attendances,
            'todayAttendance' => $todayAttendance // Kirim status hari ini ke View
        ])->layout('layouts.app');
    }
}