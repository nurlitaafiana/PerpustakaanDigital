<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Update otomatis status peminjaman yang telah melewati batas tanggal kembali menjadi 'Terlambat'
        Borrowing::where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', Carbon::now()->startOfDay())
            ->update(['status' => 'Terlambat']);

        $user = \Illuminate\Support\Facades\Auth::user();

        // Tampilan khusus jika login sebagai Siswa / Peminjam
        if ($user && $user->role === 'Siswa') {
            $memberId = $user->member_id;

            $peminjamanSayaAktif = Borrowing::with('book')
                ->where('member_id', $memberId)
                ->whereIn('status', ['Dipinjam', 'Terlambat'])
                ->get();

            $totalDipinjamSaya = Borrowing::where('member_id', $memberId)->count();
            $totalSelesaiSaya = Borrowing::where('member_id', $memberId)
                ->where('status', 'Dikembalikan')
                ->count();

            $totalBukuTersedia = Book::where('stok', '>', 0)->count();

            $rekomendasiBuku = Book::where('stok', '>', 0)
                ->latest()
                ->take(6)
                ->get();

            return view('dashboard.siswa', compact(
                'peminjamanSayaAktif',
                'totalDipinjamSaya',
                'totalSelesaiSaya',
                'totalBukuTersedia',
                'rekomendasiBuku'
            ));
        }

        // Data Statistik Petugas / Administrator
        $totalBuku = Book::count();
        $totalStok = Book::sum('stok');
        $totalAnggota = Member::count();
        $peminjamanAktif = Borrowing::whereIn('status', ['Dipinjam', 'Terlambat'])->count();
        $totalPeminjaman = Borrowing::count();

        // 6 Peminjaman Terbaru
        $peminjamanTerbaru = Borrowing::with(['member', 'book', 'user'])
            ->latest()
            ->take(6)
            ->get();

        // Rekap buku dengan stok menipis (<= 2)
        $bukuStokMenipis = Book::where('stok', '<=', 2)
            ->orderBy('stok', 'asc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalBuku',
            'totalStok',
            'totalAnggota',
            'peminjamanAktif',
            'totalPeminjaman',
            'peminjamanTerbaru',
            'bukuStokMenipis'
        ));
    }
}
