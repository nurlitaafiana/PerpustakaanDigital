@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-0">
    <!-- Welcome Header Banner -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Dashboard Perpustakaan</h3>
            <p class="text-muted mb-0">Selamat datang di sistem manajemen perpustakaan sekolah digital.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('borrowings.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle"></i> Catat Peminjaman
            </a>
            <a href="{{ route('books.create') }}" class="btn btn-outline-primary bg-white shadow-sm">
                <i class="bi bi-book"></i> Tambah Buku
            </a>
        </div>
    </div>

    <!-- 4 Statistics Cards -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Judul Buku -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Total Judul Buku</span>
                            <h2 class="fw-bold text-dark mt-1 mb-0">{{ number_format($totalBuku) }}</h2>
                            <small class="text-muted">Koleksi judul buku</small>
                        </div>
                        <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                            <i class="bi bi-journal-album"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Stok Tersedia -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Stok Buku Fisik</span>
                            <h2 class="fw-bold text-success mt-1 mb-0">{{ number_format($totalStok) }}</h2>
                            <small class="text-muted">Buku tersedia di rak</small>
                        </div>
                        <div class="stat-icon-wrapper bg-success-subtle text-success">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Anggota Siswa -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Anggota Terdaftar</span>
                            <h2 class="fw-bold text-info mt-1 mb-0">{{ number_format($totalAnggota) }}</h2>
                            <small class="text-muted">Siswa perpustakaan</small>
                        </div>
                        <div class="stat-icon-wrapper bg-info-subtle text-info">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Peminjaman Aktif -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Sedang Dipinjam</span>
                            <h2 class="fw-bold text-warning mt-1 mb-0">{{ number_format($peminjamanAktif) }}</h2>
                            <small class="text-muted">Dari {{ $totalPeminjaman }} total transaksi</small>
                        </div>
                        <div class="stat-icon-wrapper bg-warning-subtle text-warning">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section: Latest Borrowings & Low Stock Books -->
    <div class="row g-4">
        <!-- Tabel Peminjaman Terbaru -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-primary"></i> Transaksi Peminjaman Terbaru
                        </h5>
                    </div>
                    <a href="{{ route('borrowings.index') }}" class="btn btn-sm btn-light border">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Peminjam</th>
                                <th>Judul Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tenggat</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjamanTerbaru as $b)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $b->member->nama ?? 'Siswa' }}</div>
                                        <small class="text-muted">{{ $b->member->kelas ?? '-' }} • NIS: {{ $b->member->nis ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark text-truncate" style="max-width: 220px;">
                                            {{ $b->book->judul ?? 'Buku' }}
                                        </div>
                                        <small class="text-muted">{{ $b->book->kategori ?? 'Umum' }}</small>
                                    </td>
                                    <td>{{ $b->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td>{{ $b->tanggal_kembali->format('d/m/Y') }}</td>
                                    <td>
                                        @if($b->status === 'Dipinjam')
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                                <i class="bi bi-hourglass-top me-1"></i> Dipinjam
                                            </span>
                                        @elseif($b->status === 'Dikembalikan')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                                <i class="bi bi-check-circle me-1"></i> Dikembalikan
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">
                                                <i class="bi bi-exclamation-octagon me-1"></i> Terlambat
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('borrowings.show', $b->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($b->status !== 'Dikembalikan')
                                            <form action="{{ route('borrowings.return', $b->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Kembalikan Buku">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                        Belum ada data peminjaman buku.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Kolom: Peringatan Stok & Info Cepat -->
        <div class="col-12 col-lg-4">
            <!-- Card Peringatan Stok Menipis -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle text-warning"></i> Perhatian Stok Buku
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($bukuStokMenipis as $buku)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                                <div class="me-2 text-truncate">
                                    <div class="fw-medium text-dark text-truncate small">{{ $buku->judul }}</div>
                                    <small class="text-muted">{{ $buku->kategori }}</small>
                                </div>
                                <span class="badge {{ $buku->stok == 0 ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill">
                                    {{ $buku->stok }} tersisa
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted small py-3">
                                Semua buku memiliki stok yang cukup.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Card Informasi Petugas Piket -->
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-white text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem;">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-white">{{ Auth::user()->name }}</h6>
                            <small class="text-white-50">{{ Auth::user()->role }} • Perpustakaan</small>
                        </div>
                    </div>
                    <p class="small text-white-50 mb-3">
                        Gunakan sistem ini untuk mendata peminjaman siswa dan memastikan inventaris buku sekolah tetap akurat.
                    </p>
                    <a href="{{ route('profile.show') }}" class="btn btn-sm btn-light text-primary fw-semibold">
                        <i class="bi bi-gear"></i> Kelola Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
