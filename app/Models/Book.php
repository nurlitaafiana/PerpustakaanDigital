<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'kategori',
        'stok',
        'deskripsi',
    ];

    /**
     * Relasi ke transaksi peminjaman buku ini.
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Peminjaman aktif (buku yang sedang dipinjam).
     */
    public function activeBorrowings()
    {
        return $this->hasMany(Borrowing::class)->whereIn('status', ['Dipinjam', 'Terlambat']);
    }
}
