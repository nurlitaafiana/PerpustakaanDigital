<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Menampilkan daftar buku dengan fitur pencarian dan filter kategori.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');

        $query = Book::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('penerbit', 'like', "%{$search}%");
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $books = $query->latest()->paginate(10)->withQueryString();

        // Daftar kategori unik untuk filter dropdown
        $daftarKategori = Book::select('kategori')->distinct()->pluck('kategori');

        return view('books.index', compact('books', 'search', 'kategori', 'daftarKategori'));
    }

    /**
     * Menampilkan formulir penambahan buku baru.
     */
    public function create()
    {
        $kategoriList = [
            'Fiksi',
            'Sains & Teknologi',
            'Matematika',
            'Bahasa & Sastra',
            'Sejarah',
            'Komputer & IT',
            'Ensiklopedia',
            'Agama & Budi Pekerti',
            'Sosial & Budaya',
            'Lainnya',
        ];

        return view('books.create', compact('kategoriList'));
    }

    /**
     * Menyimpan data buku baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'kategori' => ['required', 'string', 'max:100'],
            'stok' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ], [
            'judul.required' => 'Judul buku wajib diisi.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
            'kategori.required' => 'Kategori buku wajib dipilih/diisi.',
            'stok.required' => 'Stok buku wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh bernilai negatif.',
        ]);

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku "' . $validated['judul'] . '" berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail buku beserta riwayat peminjamannya.
     */
    public function show(Book $book)
    {
        $book->load(['borrowings.member', 'borrowings.user']);

        return view('books.show', compact('book'));
    }

    /**
     * Menampilkan formulir edit buku.
     */
    public function edit(Book $book)
    {
        $kategoriList = [
            'Fiksi',
            'Sains & Teknologi',
            'Matematika',
            'Bahasa & Sastra',
            'Sejarah',
            'Komputer & IT',
            'Ensiklopedia',
            'Agama & Budi Pekerti',
            'Sosial & Budaya',
            'Lainnya',
        ];

        return view('books.edit', compact('book', 'kategoriList'));
    }

    /**
     * Memperbarui data buku di database.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'kategori' => ['required', 'string', 'max:100'],
            'stok' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ], [
            'judul.required' => 'Judul buku wajib diisi.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
            'kategori.required' => 'Kategori buku wajib dipilih/diisi.',
            'stok.required' => 'Stok buku wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh bernilai negatif.',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')
            ->with('success', 'Data buku "' . $book->judul . '" berhasil diperbarui.');
    }

    /**
     * Menghapus buku dari database.
     */
    public function destroy(Book $book)
    {
        // Cek apakah buku sedang dalam status dipinjam
        $activeBorrowings = $book->activeBorrowings()->count();
        if ($activeBorrowings > 0) {
            return redirect()->route('books.index')
                ->with('error', 'Buku tidak dapat dihapus karena masih ada ' . $activeBorrowings . ' transaksi peminjaman aktif.');
        }

        $judul = $book->judul;
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Buku "' . $judul . '" berhasil dihapus.');
    }
}
