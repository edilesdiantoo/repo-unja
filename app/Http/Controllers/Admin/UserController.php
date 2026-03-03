<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:dosen,user,admin,superadmin', // Tambahkan dosen di sini
            'name' => 'required|string|max:255',
            'identity_number' => 'required|string|max:50|unique:users,identity_number', // Validasi Unik
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'identity_number' => $request->identity_number,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Menampilkan form edit

    // Menampilkan form edit
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    // Memproses perubahan data
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role' => 'required|in:dosen,user,admin,superadmin',
            'name' => 'required|string|max:255',
            'identity_number' => 'required|string|max:50|unique:users,identity_number,'.$id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8', // Password boleh kosong jika tidak diubah
        ]);

        $data = [
            'name' => $request->name,
            'identity_number' => $request->identity_number,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Opsional: Cegah menghapus diri sendiri
        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function updateStatus($id)
    {
        $user = User::findOrFail($id);

        // Toggle status (jika 1 jadi 0, jika 0 jadi 1)
        $user->is_active = ! $user->is_active;
        $user->save();

        $pesan = $user->is_active ? 'Akun berhasil diaktifkan.' : 'Akun berhasil dinonaktifkan.';

        return redirect()->back()->with('success', $pesan);
    }
}
