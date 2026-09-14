<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi pengguna.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Menampilkan form registrasi pengguna/petugas baru.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Memproses pendaftaran pengguna baru.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:Siswa,Petugas'],
            'nis' => ['nullable', 'required_if:role,Siswa', 'string', 'max:50'],
            'kelas' => ['nullable', 'required_if:role,Siswa', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'role.required' => 'Pilih peran pendaftaran.',
            'nis.required_if' => 'NIS wajib diisi untuk pendaftaran siswa.',
            'kelas.required_if' => 'Kelas wajib diisi untuk pendaftaran siswa.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $memberId = null;
        if ($validated['role'] === 'Siswa') {
            $member = \App\Models\Member::firstOrCreate(
                ['nis' => $validated['nis']],
                [
                    'nama' => $validated['name'],
                    'kelas' => $validated['kelas'],
                    'email' => $validated['email'],
                    'nomor_telepon' => $request->input('nomor_telepon', '081234567890'),
                    'alamat' => $request->input('alamat', 'Alamat siswa terdaftar'),
                ]
            );
            $memberId = $member->id;
        }

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => $validated['role'],
            'member_id' => $memberId,
        ]);

        return redirect()->route('login')
            ->with('success', 'Pendaftaran akun ' . $validated['role'] . ' berhasil! Silakan masuk.');
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
