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

    public $selectedMonth;

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->format('Y-m');
    }

    public function generatePayroll()
        {
            $date = Carbon::parse($this->selectedMonth);
            $month = $date->month;
            $year = $date->year;
            $monthString = $date->format('m-Y');

            $employees = User::where('role', '!=', 'administrator')->get();
            $count = 0;

            foreach ($employees as $emp) {
                $exists = Payroll::where('user_id', $emp->id)
                    ->where('month', $monthString)
                    ->exists();

                if (!$exists) {
                    $basicSalary = $emp->position ? $emp->position->basic_salary : 0;

                    $totalOvertime = Attendance::where('user_id', $emp->id)
                        ->whereYear('date', $year)
                        ->whereMonth('date', $month)
                        ->sum('overtime_pay');

                    $grandTotal = $basicSalary + $totalOvertime;

                    Payroll::create([
                        'user_id' => $emp->id,
                        'month' => $monthString,
                        'basic_salary' => $basicSalary,
                        'overtime_salary' => $totalOvertime,
                        'total_salary' => $grandTotal,
                        'status' => 'paid'
                    ]);

                    $count++;
                }
            }

            if ($count > 0) {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'activity' => 'Generated Payroll',
                    'details' => "Generated payroll for $count employees ($monthString)",
                    'ip_address' => request()->ip()
                ]);
                
                session()->flash('message', "Success! Generated payroll for $count employees.");
            } else {
                session()->flash('error', "Payroll for this month already exists or no employees found.");
            }
        }


    public function render()
    {
        // Query Dasar
        $query = Payroll::with('user.position')->latest();

        // LOGIKA PENENTU:
        if (auth()->user()->role === 'user') {
            // A. Kalau dia STAFF BIASA:
            // Cuma tampilkan data milik dia sendiri
            $query->where('user_id', auth()->id());
        } else {
            // B. Kalau dia ADMIN/SUPERVISOR:
            // Tampilkan data sesuai filter Bulan
            $monthKey = Carbon::parse($this->selectedMonth)->format('m-Y');
            $query->where('month', $monthKey);
        }

        $payrolls = $query->paginate(10);

        return view('livewire.payroll-index', [
            'payrolls' => $payrolls
        ])->layout('layouts.app');
    }
}
