<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Position;
use App\Models\Attendance;
use Carbon\Carbon;        
use App\Models\Leave;

class Dashboard extends Component
{

    public function checkIn()
    {
        // 1. Cek apakah hari ini sudah absen?
        $exists = Attendance::where('user_id', auth()->id())
                    ->whereDate('date', Carbon::today())
                    ->exists();

        if ($exists) {return;}
        
        // 2. ATURAN JAM 8 PAGI
        $jamMasuk = Carbon::now();
        $batasTelat = Carbon::today()->setTime(8, 0, 0); // Jam 08:00:00

        // Kalau masuk lewat jam 8, status = 'late', kalau ontime = 'present'
        $status = $jamMasuk->gt($batasTelat) ? 'late' : 'present';

        // 3. Simpan
        Attendance::create([
            'user_id' => auth()->id(),
            'date' => Carbon::today(),
            'check_in_time' => $jamMasuk,
            'status' => $status, // Simpan status telat/hadir
            'overtime_pay' => 0 // Default 0
        ]);

        // 3. Refresh halaman/komponen biar tampilan berubah
        $this->dispatch('refresh-dashboard'); 
    }

    public function checkOut()
    {
        // 1. Cari data absen hari ini milik user
        $attendance = Attendance::where('user_id', auth()->id())
                        ->whereDate('date', Carbon::today())
                        ->first();

        if (!$attendance) {
            return; // Belum check in kok mau check out?
        }

        $jamPulang = Carbon::now();
        // $jamPulang = Carbon::today()->createFromTime(18, 30, 0);
        
        // Aturan Lembur: Minimal jam 18:30 (17:00 + 1.5 jam)
        $batasLembur = Carbon::today()->setTime(18, 30, 0);

        $bonus = 0;

        if ($attendance->status == 'present' && $jamPulang->gte($batasLembur)) {
            $bonus = 100000;
        }

        // Update Data
        $attendance->update([
            'check_out_time' => $jamPulang,
            'overtime_pay' => $bonus
        ]);
    }

    public function render()
    {
        $user = auth()->user();
        $today = Carbon::today();

        // A. Variabel Default
        $totalEmployees = 0;
        $presentToday = 0;
        $lateToday = 0;
        $onLeaveToday = 0;

        // B. Logic Pembeda (Admin vs User Biasa)
        if ($user->role !== 'user') {
            // --- LOGIC ADMIN (Lihat Data Satu Kantor) ---
            $totalEmployees = User::where('role', '!=', 'administrator')->count();
            
            $presentToday = Attendance::whereDate('date', $today)->count();
            
            $lateToday = Attendance::whereDate('date', $today)
                        ->where('status', 'late')
                        ->count();

            $onLeaveToday = Leave::where('status', 'approved')
                        ->whereDate('start_date', '<=', $today)
                        ->whereDate('end_date', '>=', $today)
                        ->count();
        } else {
            // --- LOGIC USER (Lihat Data Diri Sendiri Bulan Ini) ---
            // Kita ubah variabel 'totalEmployees' jadi hitungan hadir bulan ini biar card-nya kepakai
            $presentToday = Attendance::where('user_id', $user->id)
                        ->whereMonth('date', Carbon::now()->month)
                        ->count();
            
            $lateToday = Attendance::where('user_id', $user->id)
                        ->whereMonth('date', Carbon::now()->month)
                        ->where('status', 'late')
                        ->count();
                        
            $onLeaveToday = Leave::where('user_id', $user->id)
                        ->where('status', 'pending')
                        ->count();
        }

        // C. Data Tombol Absen (Wajib ada biar tombol Check In/Out muncul)
        $todayAttendance = Attendance::where('user_id', auth()->id())
                            ->whereDate('date', $today)
                            ->first();

        // D. Data Grafik (Tetap pakai kodingan lama kamu)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = Attendance::where('date', $date->format('Y-m-d'))
                ->whereNotNull('check_in_time')
                ->count();
        }

        return view('livewire.dashboard', [
            'totalEmployees'  => $totalEmployees, // Kalau user, ini isinya 0 (bisa diabaikan di view)
            'presentToday'    => $presentToday,
            'lateToday'       => $lateToday,
            'onLeaveToday'    => $onLeaveToday,
            'todayAttendance' => $todayAttendance, // Untuk tombol Check In/Out
            'chartLabels'     => $chartLabels,
            'chartData'       => $chartData
        ])->layout('layouts.app');
    }
}