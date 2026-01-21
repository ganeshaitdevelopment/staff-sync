<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\ActivityLog;
use Carbon\Carbon;

class AttendanceWidget extends Component
{
    public $todayAttendance;

    public function mount()
    {
        // Cek apakah hari ini user sudah absen?
        $this->todayAttendance = Attendance::where('user_id', auth()->id())
            ->where('date', today())
            ->first();
    }

public function checkIn()
    {
        // ATURAN 1: Jam Masuk 08:00
        $scheduleTime = Carbon::createFromTime(8, 0, 0); // Jam 08:00:00 Hari ini
        
        // Logika Telat: Jika Sekarang > Jam 8
        $status = now()->greaterThan($scheduleTime) ? 'late' : 'present';

        // Simpan Data
        Attendance::create([
            'user_id' => auth()->id(),
            'date' => today(),
            'check_in_time' => now(),
            'status' => $status,
            'overtime_pay' => 0 // Default 0
        ]);

        // Catat Log
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Check In',
            'details' => 'User checked in (' . ucfirst($status) . ') at ' . now()->format('H:i'),
            'ip_address' => request()->ip()
        ]);

        $this->mount();
        
        // Pesan beda kalau telat
        if($status == 'late') {
            session()->flash('message', 'You are late! Check in recorded.');
        } else {
            session()->flash('message', 'On Time! Check in successful.');
        }
    }

    public function checkOut()
    {
        if ($this->todayAttendance) {
            
            // $waktuSekarang = Carbon::createFromTime(18, 30, 0);
            $waktuSekarang = now();
            $standardGoHomeTime = Carbon::createFromTime(17, 0, 0); // Jam 17:00
            $overtimePay = 0;

            if ($this->todayAttendance->status !== 'late' && $waktuSekarang->greaterThan($standardGoHomeTime)) {
                
                $overtimeDuration = $standardGoHomeTime->diffInMinutes($waktuSekarang);

                if ($overtimeDuration >= 90) {
                    $overtimePay = 100000;
                }
            }

            // Update Database
            $this->todayAttendance->update([
                'check_out_time' => $waktuSekarang,
                'overtime_pay' => $overtimePay
            ]);

            // Catat Log
            $logDetail = 'User checked out.';
            if($overtimePay > 0) $logDetail .= ' (Overtime Bonus: 100k)';

            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Check Out',
                'details' => $logDetail,
                'ip_address' => request()->ip()
            ]);

            $this->mount();
            session()->flash('message', 'Check Out Successful! Overtime: Rp ' . number_format($overtimePay));
        }
    }

    public function render()
    {
        return view('livewire.attendance-widget');
    }
}