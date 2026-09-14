<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'member_id',
        'book_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_pengembalian_aktual',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_pengembalian_aktual' => 'date',
    ];

    /**
     * Relasi ke anggota yang meminjam.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Relasi ke buku yang dipinjam.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Relasi ke user / petugas yang melayani.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cek apakah status peminjaman sudah terlambat.
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'Dikembalikan') {
            return false;
        }

        return Carbon::now()->startOfDay()->gt($this->tanggal_kembali);
    }
}
