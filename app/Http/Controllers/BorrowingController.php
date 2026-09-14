<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    /**
     * Menampilkan daftar transaksi peminjaman buku.
     */
    public function index(Request $request)
    {
        // Periksa dan perbarui status peminjaman yang telah melewati jatuh tempo
        Borrowing::where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', Carbon::now()->startOfDay())
            ->update(['status' => 'Terlambat']);

        $status = $request->input('status');
        $search = $request->input('search');

        $user = Auth::user();
        $query = Borrowing::with(['member', 'book', 'user']);

        // Jika user adalah Siswa / Peminjam, hanya tampilkan riwayat miliknya sendiri
        if ($user && $user->role === 'Siswa') {
            $query->where('member_id', $user->member_id);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('member', function ($mq) use ($search) {
                    $mq->where('nama', 'like', "%{$search}%")
                       ->orWhere('nis', 'like', "%{$search}%");
                })->orWhereHas('book', function ($bq) use ($search) {
                    $bq->where('judul', 'like', "%{$search}%");
                });
            });
        }

        $borrowings = $query->latest('tanggal_pinjam')->paginate(10)->withQueryString();

        return view('borrowings.index', compact('borrowings', 'status', 'search'));
    }

    /**
     * Menampilkan form transaksi peminjaman baru.
     */
    public function create()
    {
        $members = Member::orderBy('nama', 'asc')->get();
        // Hanya buku yang memiliki stok > 0 yang bisa dipinjam
        $books = Book::where('stok', '>', 0)->orderBy('judul', 'asc')->get();

        return view('borrowings.create', compact('members', 'books'));
    }

    /**
     * Menyimpan transaksi peminjaman baru dan otomatis mengurangi stok buku.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'book_id' => ['required', 'exists:books,id'],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_kembali' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'catatan' => ['nullable', 'string'],
        ], [
            'member_id.required' => 'Pilih anggota peminjam.',
            'book_id.required' => 'Pilih buku yang akan dipinjam.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_kembali.required' => 'Tanggal rencana pengembalian wajib diisi.',
            'tanggal_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->stok < 1) {
            return back()->withErrors(['book_id' => 'Stok buku ini sedang habis.'])->withInput();
        }

        // Tentukan status awal berdasarkan tanggal
        $status = 'Dipinjam';
        if (Carbon::parse($validated['tanggal_kembali'])->isPast() && !Carbon::parse($validated['tanggal_kembali'])->isToday()) {
            $status = 'Terlambat';
        }

        $borrowing = Borrowing::create([
            'user_id' => Auth::id(),
            'member_id' => $validated['member_id'],
            'book_id' => $validated['book_id'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'status' => $status,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        // Kurangi stok buku
        $book->decrement('stok');

        return redirect()->route('borrowings.index')
            ->with('success', 'Peminjaman berhasil dicatat! Stok buku "' . $book->judul . '" otomatis berkurang 1.');
    }

    /**
     * Menampilkan rincian transaksi peminjaman.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['member', 'book', 'user']);

        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Menampilkan form edit transaksi peminjaman.
     */
    public function edit(Borrowing $borrowing)
    {
        $borrowing->load(['member', 'book']);
        $members = Member::orderBy('nama', 'asc')->get();
        $books = Book::orderBy('judul', 'asc')->get();

        return view('borrowings.edit', compact('borrowing', 'members', 'books'));
    }

    /**
     * Memperbarui transaksi peminjaman.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_kembali' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'status' => ['required', 'in:Dipinjam,Dikembalikan,Terlambat'],
            'catatan' => ['nullable', 'string'],
        ], [
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_kembali.required' => 'Tanggal batas kembali wajib diisi.',
            'status.required' => 'Status peminjaman wajib dipilih.',
        ]);

        $statusLama = $borrowing->status;
        $statusBaru = $validated['status'];

        // Jika status diubah menjadi Dikembalikan dan sebelumnya bukan Dikembalikan, pulihkan stok buku
        if ($statusBaru === 'Dikembalikan' && $statusLama !== 'Dikembalikan') {
            $borrowing->tanggal_pengembalian_aktual = Carbon::now()->toDateString();
            $borrowing->book->increment('stok');
        } elseif ($statusBaru !== 'Dikembalikan' && $statusLama === 'Dikembalikan') {
            // Jika sebelumnya Dikembalikan lalu diubah kembali jadi Dipinjam/Terlambat
            $borrowing->tanggal_pengembalian_aktual = null;
            $borrowing->book->decrement('stok');
        }

        $borrowing->update([
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'status' => $statusBaru,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('borrowings.index')
            ->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    /**
     * Aksi cepat untuk mencatat pengembalian buku.
     */
    public function returnBook(Borrowing $borrowing)
    {
        if ($borrowing->status === 'Dikembalikan') {
            return back()->with('info', 'Buku ini sudah berstatus dikembalikan sebelumnya.');
        }

        $borrowing->update([
            'status' => 'Dikembalikan',
            'tanggal_pengembalian_aktual' => Carbon::now()->toDateString(),
        ]);

        // Tambah stok buku kembali
        $borrowing->book->increment('stok');

        return redirect()->route('borrowings.index')
            ->with('success', 'Buku "' . $borrowing->book->judul . '" berhasil dikembalikan! Stok buku bertambah 1.');
    }

    /**
     * Menghapus catatan transaksi peminjaman.
     */
    public function destroy(Borrowing $borrowing)
    {
        // Jika masih berstatus dipinjam/terlambat saat dihapus, kembalikan stok buku
        if (in_array($borrowing->status, ['Dipinjam', 'Terlambat'])) {
            $borrowing->book->increment('stok');
        }

        $borrowing->delete();

        return redirect()->route('borrowings.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
