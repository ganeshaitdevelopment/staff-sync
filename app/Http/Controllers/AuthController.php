<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog; // Jangan lupa import model ini

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function index()
    {
        return view('auth.login');
    }

    // 2. Proses Login (The Logic)
    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'identity' => ['required', 'string'], // Bisa Email atau No HP
            'password' => ['required'],
        ]);

        // Cek apakah inputnya Email atau No HP?
        // Kalau formatnya email, kita anggap email. Kalau bukan, kita anggap no hp.
        $loginType = filter_var($request->identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        // Coba Login
        if (Auth::attempt([$loginType => $request->identity, 'password' => $request->password])) {
            
            $request->session()->regenerate();

            // CATAT LOG (Penting buat Admin HR)
            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity' => 'Login Success',
                'ip_address' => $request->ip(),
                'details' => 'User logged in via ' . $loginType,
            ]);

            return redirect()->intended('dashboard');
        }

        // Kalau Gagal
        return back()->withErrors([
            'identity' => 'Kombinasi akun dan password salah.',
        ])->onlyInput('identity');
    }

    // 3. Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}