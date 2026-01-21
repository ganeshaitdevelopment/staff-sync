<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Position;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = User::where('role', '!=', 'administrator')->count();
        $totalPositions = Position::count();
        $user = auth()->user();
        $query = ActivityLog::with('user')->latest();
        if ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }

        $recentLogs = $query->take(5)->get();

        $newEmployeesThisMonth = User::where('role', '!=', 'administrator')
            ->whereMonth('created_at', now()->month)
            ->count();

        return view('dashboard', [
            'totalEmployees' => $totalEmployees,
            'totalPositions' => $totalPositions,
            'recentLogs' => $recentLogs,
            'newEmployeesThisMonth' => $newEmployeesThisMonth
        ]);
    }
}