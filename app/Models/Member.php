<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nis',
        'kelas',
        'email',
        'nomor_telepon',
        'alamat',
    ];

    /**
     * Relasi ke transaksi peminjaman anggota ini.
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Peminjaman aktif milik anggota.
     */
    public function activeBorrowings()
    {
        return $this->hasMany(Borrowing::class)->whereIn('status', ['Dipinjam', 'Terlambat']);
    }
}
