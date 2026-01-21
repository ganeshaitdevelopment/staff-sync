<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Position;
use App\Models\Attendance; // <--- Wajib Import
use Carbon\Carbon;         // <--- Wajib Import

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

        // SYARAT DAPAT 100RB:
        // 1. Tidak Telat (status == 'present')
        // 2. Pulang lebih dari jam 18:30
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
        // 1. Data Card Statistik
        $totalEmployees = User::where('role', 'user')->count();
        $totalPositions = Position::count();
        
        // 2. Cek Absen Harian User yang Login (INI YANG DICARI ERROR TADI)
        $todayAttendance = Attendance::where('user_id', auth()->id())
                            ->where('date', date('Y-m-d'))
                            ->first();

        // 3. Data untuk Grafik (7 Hari Terakhir)
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            // Label Sumbu X (Contoh: "21 Jan")
            $chartLabels[] = $date->format('d M');
            
            // Data Sumbu Y (Jumlah Karyawan Hadir)
            $chartData[] = Attendance::where('date', $date->format('Y-m-d'))
                ->whereNotNull('check_in_time')
                ->count();
        }

        // 4. Kirim Semua Data ke View
        return view('livewire.dashboard', [
            'totalEmployees'  => $totalEmployees,
            'totalPositions'  => $totalPositions,
            'todayAttendance' => $todayAttendance,
            'chartLabels'     => $chartLabels,
            'chartData'       => $chartData
        ])->layout('layouts.app');
    }
}