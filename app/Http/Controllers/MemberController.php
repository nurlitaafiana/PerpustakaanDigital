<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Menampilkan daftar anggota perpustakaan.
     */
    public function index(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role === 'Siswa') {
            return redirect()->route('dashboard')->with('error', 'Halaman data anggota hanya dapat diakses oleh Petugas atau Administrator.');
        }

        $search = $request->input('search');

        $query = Member::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(10)->withQueryString();

        return view('members.index', compact('members', 'search'));
    }

    /**
     * Menampilkan formulir pendaftaran anggota baru.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Menyimpan data anggota baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:50', 'unique:members,nis'],
            'kelas' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ], [
            'nama.required' => 'Nama lengkap siswa wajib diisi.',
            'nis.required' => 'Nomor Induk Siswa (NIS) wajib diisi.',
            'nis.unique' => 'NIS ini sudah terdaftar sebelumnya.',
            'kelas.required' => 'Kelas wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nomor_telepon.required' => 'Nomor telepon / WA wajib diisi.',
            'alamat.required' => 'Alamat tempat tinggal wajib diisi.',
        ]);

        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Anggota siswa "' . $validated['nama'] . '" berhasil didaftarkan.');
    }

    /**
     * Menampilkan profil lengkap anggota dan riwayat peminjamannya.
     */
    public function show(Member $member)
    {
        $member->load(['borrowings.book', 'borrowings.user']);

        return view('members.show', compact('member'));
    }

    /**
     * Menampilkan formulir edit data anggota.
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Memperbarui data anggota di database.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:50', Rule::unique('members', 'nis')->ignore($member->id)],
            'kelas' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ], [
            'nama.required' => 'Nama lengkap siswa wajib diisi.',
            'nis.required' => 'Nomor Induk Siswa (NIS) wajib diisi.',
            'nis.unique' => 'NIS ini sudah digunakan oleh siswa lain.',
            'kelas.required' => 'Kelas wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nomor_telepon.required' => 'Nomor telepon / WA wajib diisi.',
            'alamat.required' => 'Alamat tempat tinggal wajib diisi.',
        ]);

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Data anggota "' . $member->nama . '" berhasil diperbarui.');
    }

    /**
     * Menghapus data anggota.
     */
    public function destroy(Member $member)
    {
        $activeBorrowings = $member->activeBorrowings()->count();
        if ($activeBorrowings > 0) {
            return redirect()->route('members.index')
                ->with('error', 'Anggota tidak dapat dihapus karena masih meminjam ' . $activeBorrowings . ' buku.');
        }

        $nama = $member->nama;
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Anggota "' . $nama . '" berhasil dihapus.');
    }
}
