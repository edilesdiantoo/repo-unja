<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            $user->update([
                'last_login_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'description' => 'Login ke dalam sistem',
            ]);

            if ($user->role === 'admin' || $user->role === 'superadmin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('user.dashboard'));
        }

        return back()->with('error', 'Username/Email atau Password salah.');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => 'Logout dari sistem',
            ]);
        }

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

        $user = User::where('identity_number', $identity)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        session()->forget('reset_identity');

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui! Silakan login.');
    }

    // --- FITUR REGISTRASI ---

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'identity_number' => 'required|string|max:50|unique:users,identity_number',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'identity_number.required' => 'NIM / NIDN wajib diisi.',
            'identity_number.unique' => 'NIM / NIDN sudah terdaftar.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'identity_number' => $request->identity_number,
            'email' => $request->email,
            'role' => $request->role, // user atau dosen
            'is_active' => 1,
            'password' => bcrypt($request->password),
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'description' => 'Mendaftar akun baru sebagai user/mahasiswa',
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di Repositori FH UNJA.');
    }
}
