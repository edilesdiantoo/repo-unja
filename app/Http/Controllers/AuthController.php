<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- WAJIB TAMBAHKAN INI

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'identity_number';

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // --- UPDATE TERAKHIR LOGIN DI SINI ---
            $user->update([
                'last_login_at' => now(),
            ]);
            // -------------------------------------

            // --- TAMBAHKAN LOG LOGIN DI SINI ---
            ActivityLog::create([
                'user_id' => $user->id,
                'description' => 'Login ke dalam sistem',
            ]);
            // -----------------------------------

            if ($user->role === 'admin' || $user->role === 'superadmin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('user.dashboard'));
        }

        return back()->with('error', 'Username/Email atau Password salah.');
    }

    public function logout(Request $request)
    {
        // --- TAMBAHKAN LOG LOGOUT DI SINI ---
        // Catat sebelum proses logout menghapus session
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => 'Logout dari sistem',
            ]);
        }
        // ------------------------------------

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $request->validate([
            'identity_number' => 'required|string|exists:users,identity_number',
        ], [
            'identity_number.exists' => 'Nomor Identitas (NIM/NIDN) tidak ditemukan.',
        ]);

        // Jika ketemu, simpan identitas di session sementara dan arahkan ke halaman input password baru
        session(['reset_identity' => $request->identity_number]);

        return view('auth.update-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $identity = session('reset_identity');

        if (! $identity) {
            return redirect()->route('password.request')->withErrors(['identity_number' => 'Sesi habis, silakan masukkan NIM kembali.']);
        }

        // Update Password di Database
        $user = User::where('identity_number', $identity)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // Hapus session reset
        session()->forget('reset_identity');

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui! Silakan login.');
    }
}
