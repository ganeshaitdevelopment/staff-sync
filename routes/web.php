<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Livewire\Dashboard; 
use App\Livewire\EmployeeIndex;
use App\Livewire\PositionIndex;    
use App\Livewire\ActivityLogIndex;
use App\Livewire\AttendanceIndex;
use App\Livewire\PayrollIndex;
use App\Http\Controllers\PayrollController;
use App\Livewire\UserProfile;
use App\Livewire\LeaveIndex;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

// 1. Route untuk Tamu (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
});

// 2. Route untuk Member (Sudah Login)
Route::middleware('auth')->group(function () {
    
    // 2. UBAH BARIS INI (Penting!):
    // Dari [DashboardController::class, 'index'] MENJADI Dashboard::class
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/attendance', AttendanceIndex::class)->name('attendance.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/payroll/{payroll}/download', [PayrollController::class, 'download'])->name('payroll.download');
    Route::get('/profile', UserProfile::class)->name('profile');
    
    Route::get('/payroll', PayrollIndex::class)->name('payroll.index');
    Route::get('/leaves', LeaveIndex::class)->name('leaves.index');

    Route::middleware(['role:administrator,supervisor'])->group(function () {
        Route::get('/employees', EmployeeIndex::class)->name('employees.index');
        Route::get('/positions', PositionIndex::class)->name('positions.index');
        Route::get('/logs', ActivityLogIndex::class)->name('logs.index');
    });
    Route::get('/attendance/export', function () {
        return Excel::download(new AttendanceExport, 'laporan_absensi.xlsx');
    })->name('attendance.export');
    
});

// Redirect halaman depan ke login
Route::get('/', function () {
    return redirect()->route('login');
});