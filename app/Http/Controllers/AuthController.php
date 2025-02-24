<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);
    
        // Cek apakah email ada di database
        $user = User::where('email', $request->email)->first();
    
        // Coba login
        if ($user && Auth::attempt($credentials)) {
            // Jika role adalah 'pengguna', langsung abort 403
            if ($user->role === 'pengguna') {
                abort(403, 'Anda tidak memiliki akses.');
            }
    
            return redirect()->route('dashboard')->with('success', 'Login berhasil');
        }
    
        // Jika login gagal, kirim pesan error
        if ($user) {
            // Jika email ada tetapi password salah
            return back()->withErrors(['password' => 'Password salah'])->withInput();
        } else {
            // Jika email tidak ada
            return back()->withErrors(['email' => 'Email tidak terdaftar'])->withInput();
        }
    }
    
    
    
    
    

    // Menampilkan halaman registrasi (Hanya Pemilik)
    public function showRegister()
    {
        return view('auth.register');
    }

    
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout berhasil');
    }

   
}
