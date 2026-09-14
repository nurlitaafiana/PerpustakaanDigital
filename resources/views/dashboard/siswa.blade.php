@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="container-fluid px-0">
    <!-- Welcome Banner Siswa -->
    <div class="card border-0 shadow-sm bg-primary text-white mb-4 rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <span class="badge bg-white text-primary fw-bold mb-2 px-3 py-2 rounded-pill">
                        <i class="bi bi-mortarboard-fill me-1"></i> Portal Siswa / Peminjam
                    </span>
                    <h3 class="fw-bold text-white mb-2">Halo, {{ Auth::user()->name }}! 👋</h3>
                    <p class="text-white-50 mb-0">
                        Selamat datang di Perpustakaan Digital Sekolah. Di sini kamu bisa memantau buku yang sedang kamu pinjam dan menjelajahi ribuan koleksi buku menarik.
                    </p>
                </div>
                <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('books.index') }}" class="btn btn-light text-primary fw-semibold shadow-sm">
                        <i class="bi bi-search"></i> Jelajahi Katalog Buku
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Student Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Sedang Dipinjam -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Buku Dipinjam</span>
                            <h2 class="fw-bold {{ $peminjamanSayaAktif->count() > 0 ? 'text-warning' : 'text-dark' }} mt-1 mb-0">
                                {{ $peminjamanSayaAktif->count() }}
                            </h2>
                            <small class="text-muted">Sedang kamu bawa</small>
                        </div>
                        <div class="stat-icon-wrapper bg-warning-subtle text-warning">
                            <i class="bi bi-book-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Riwayat Selesai -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Sudah Dikembalikan</span>
                            <h2 class="fw-bold text-success mt-1 mb-0">{{ $totalSelesaiSaya }}</h2>
                            <small class="text-muted">Peminjaman selesai</small>
                        </div>
                        <div class="stat-icon-wrapper bg-success-subtle text-success">
                            <i class="bi bi-check-all"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Pernah Pinjam -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Total Riwayat</span>
                            <h2 class="fw-bold text-info mt-1 mb-0">{{ $totalDipinjamSaya }}</h2>
                            <small class="text-muted">Total transaksi pinjam</small>
                        </div>
                        <div class="stat-icon-wrapper bg-info-subtle text-info">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Koleksi Buku Tersedia -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Buku Tersedia</span>
                            <h2 class="fw-bold text-primary mt-1 mb-0">{{ $totalBukuTersedia }}</h2>
                            <small class="text-muted">Siap dipinjam di rak</small>
                        </div>
                        <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                            <i class="bi bi-collection"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Borrowings Table for Student -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-journal-arrow-up text-warning"></i> Buku yang Sedang Kamu Pinjam
            </h5>
            <a href="{{ route('borrowings.index') }}" class="btn btn-sm btn-light border">
                Lihat Semua Riwayat <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Judul Buku</th>
                        <th>Penulis & Kategori</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Waktu Pengembalian</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamanSayaAktif as $item)
                        <tr>
                            <td>
                                <strong class="text-dark">{{ $item->book->judul ?? 'Buku' }}</strong>
                            </td>
                            <td>
                                <div>{{ $item->book->penulis ?? '-' }}</div>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill" style="font-size: 0.72rem;">
                                    {{ $item->book->kategori ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $item->tanggal_pinjam->format('d F Y') }}</td>
                            <td>
                                <span class="{{ $item->status === 'Terlambat' ? 'text-danger fw-bold' : 'text-dark' }}">
                                    {{ $item->tanggal_kembali->format('d F Y') }}
                                </span>
                                @if($item->status === 'Terlambat')
                                    <small class="text-danger d-block"><i class="bi bi-exclamation-triangle"></i> Segera kembalikan ke perpustakaan</small>
                                @endif
                            </td>
                            <td>
                                @if($item->status === 'Dipinjam')
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                        <i class="bi bi-hourglass-top me-1"></i> Dipinjam
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">
                                        <i class="bi bi-exclamation-octagon me-1"></i> Terlambat
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('books.show', $item->book_id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-info-circle"></i> Info Buku
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-check-circle fs-2 text-success d-block mb-1"></i>
                                <span>Kamu sedang tidak meminjam buku. Ingin membaca? Kunjungi perpustakaan atau cari di katalog!</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recommended Books Grid -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-stars text-primary"></i> Rekomendasi Buku Menarik
            </h5>
            <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-primary">
                Katalog Lengkap <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                @foreach($rekomendasiBuku as $book)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card h-100 border p-3 rounded-3 shadow-none">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                    {{ $book->kategori }}
                                </span>
                                <span class="badge bg-success-subtle text-success rounded-pill">
                                    Stok: {{ $book->stok }}
                                </span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $book->judul }}</h6>
                            <small class="text-muted d-block mb-2">Penulis: {{ $book->penulis }}</small>
                            <p class="small text-secondary mb-3" style="min-height: 40px;">
                                {{ Str::limit($book->deskripsi ?: 'Buku koleksi perpustakaan sekolah.', 70) }}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-outline-secondary w-100">
                                    <i class="bi bi-eye"></i> Lihat Rincian
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
