<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()
            ->where('role', '!=', 'pemilik')
            ->orderByRaw('CASE WHEN deleted_at IS NULL THEN 0 ELSE 1 END') 
            ->paginate(10); 
    
        return view('users.index', compact('users'));
    }
    

    // Menampilkan halaman tambah user
    public function create()
    {
        return view('users.create');
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/', // Harus memiliki setidaknya satu huruf besar
                'regex:/[a-z]/', // Harus memiliki setidaknya satu huruf kecil
                'regex:/[0-9]/', // Harus memiliki setidaknya satu angka
                'confirmed' // Pastikan password konfirmasi cocok
            ],
            'role' => 'required|in:kasir,pengguna',
            'tipe_pelanggan' => 'nullable|in:1,2,3',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            
            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain.',
            
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung setidaknya satu huruf besar, satu huruf kecil, dan satu angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
            
            'tipe_pelanggan.in' => 'Tipe pelanggan tidak valid.',
        ]);
    
        $password = Hash::make($request->password);
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role' => $request->role,
            'tipe_pelanggan' => ($request->role == 'pengguna' && $request->filled('tipe_pelanggan')) ? $request->tipe_pelanggan : null,
        ]);
    
        return redirect()->route('users.index')->with('success', 'User berhasil dibuat!');
    }
    
    
    

    // Menampilkan halaman edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Mengupdate data user
    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/[A-Z]/', // Harus memiliki setidaknya satu huruf besar
                'regex:/[a-z]/', // Harus memiliki setidaknya satu huruf kecil
                'regex:/[0-9]/', // Harus memiliki setidaknya satu angka
                'confirmed' // Pastikan password konfirmasi cocok jika diisi
            ],
            'role' => 'required|in:kasir,pengguna',
            'tipe_pelanggan' => 'nullable|in:1,2,3',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
    
            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain.',
    
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung setidaknya satu huruf besar, satu huruf kecil, dan satu angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
    
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
    
            'tipe_pelanggan.in' => 'Tipe pelanggan tidak valid.',
        ]);
    
    
        // Update data pengguna
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->role = $request->role;
        $user->tipe_pelanggan = $request->role == 'pengguna' ? $request->tipe_pelanggan : null;
        $user->save();
    
        // Set flash message
        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');

    }
    
    


    // Menghapus user
    public function destroy(User $user)
    {
        $user->delete(); // Mengisi deleted_at otomatis
    
        if ($user->trashed()) { // Cek apakah berhasil soft delete
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
        } else {
            return redirect()->route('users.index')->with('error', 'Soft delete gagal.');
        }
    }
    
    
    

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('users.index')->with('success', 'User berhasil dikembalikan.');
    }
}