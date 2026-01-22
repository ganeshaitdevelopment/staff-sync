<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payroll;
use App\Models\User;
use App\Models\Attendance;
use App\Models\ActivityLog;
use Carbon\Carbon;

class PayrollIndex extends Component
{
    use WithPagination;

    public $monthFilter; 

    public function mount()
    {
        $this->monthFilter = Carbon::now()->format('Y-m');
    }

    public function generatePayroll()
    {
        
        $date = Carbon::createFromFormat('Y-m', $this->monthFilter);
        $month = $date->month;
        $year = $date->year;
        
        $monthString = $this->monthFilter; 

        $employees = User::where('role', '!=', 'administrator')->get();
        $count = 0;

        foreach ($employees as $emp) {
            // 1. Cek duplikasi
            $exists = Payroll::where('user_id', $emp->id)
                ->where('month', $monthString)
                ->exists();

            if (!$exists) {
                // 2. Ambil Gaji Pokok (Pastikan di tabel positions kolomnya 'salary' atau 'basic_salary')
                // Saya kasih fallback 0 kalau data posisi kosong
                $basicSalary = $emp->position ? $emp->position->basic_salary : 0; 
                // Catatan: Kalau di tabel positions kamu namanya 'basic_salary', ganti ->salary jadi ->basic_salary

                // 3. Hitung Lembur
                $totalOvertime = Attendance::where('user_id', $emp->id)
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->sum('overtime_pay');

                // 4. Hitung Total
                $grandTotal = $basicSalary + $totalOvertime;

                // 5. Simpan (Status default 'pending' dulu biar bisa dicek)
                Payroll::create([
                    'user_id' => $emp->id,
                    'month' => $monthString,
                    'basic_salary' => $basicSalary,
                    'overtime_pay' => $totalOvertime, // Sesuaikan nama kolom DB (overtime_pay)
                    'total_salary' => $grandTotal,
                    'status' => 'pending' 
                ]);

                $count++;
            }
        }

        if ($count > 0) {
            // Log Aktivitas
            if(class_exists(ActivityLog::class)) {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'activity' => 'Generated Payroll',
                    'details' => "Generated payroll for $count employees ($monthString)",
                    'ip_address' => request()->ip()
                ]);
            }
            
            session()->flash('message', "Sukses! Gaji untuk $count karyawan berhasil digenerate.");
        } else {
            session()->flash('message', "Data gaji bulan ini sudah ada atau tidak ada karyawan.");
        }
    }

    // === FITUR TAMBAHAN: UPDATE STATUS LUNAS ===
    public function markAsPaid($id)
    {
        $payroll = Payroll::find($id);
        if($payroll) {
            $payroll->update(['status' => 'paid']);
            session()->flash('message', 'Status gaji berhasil diubah menjadi LUNAS (PAID).');
        }
    }

    // === FITUR TAMBAHAN: HAPUS DATA ===
    public function delete($id)
    {
        $payroll = Payroll::find($id);
        if($payroll) {
            $payroll->delete();
            session()->flash('message', 'Data gaji berhasil dihapus.');
        }
    }

    public function render()
    {
        $query = Payroll::with('user.position')->latest();

        // Filter Data
        if (auth()->user()->role === 'user') {
            // Karyawan cuma lihat punya sendiri
            $query->where('user_id', auth()->id());
        } else {
            // Admin lihat berdasarkan filter bulan
            $query->where('month', $this->monthFilter);
        }

        return view('livewire.payroll-index', [
            'payrolls' => $query->paginate(10)
        ])->layout('layouts.app');
    }
}