@extends('layouts.app')

@section('title', 'Detail Buku - ' . $book->judul)

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('books.index') }}" class="btn btn-light border shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h3 class="fw-bold text-dark mb-0">Rincian Buku</h3>
                <small class="text-muted">Katalog ID: #{{ $book->id }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Edit Buku
            </a>
            <button type="button"
                    class="btn btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteConfirmModal"
                    data-action="{{ route('books.destroy', $book->id) }}"
                    data-name="{{ $book->judul }}">
                <i class="bi bi-trash"></i> Hapus
            </button>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Main Info Card -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill mb-2">
                                {{ $book->kategori }}
                            </span>
                            <h4 class="fw-bold text-dark mb-1">{{ $book->judul }}</h4>
                            <p class="text-muted mb-0">Oleh <span class="fw-semibold text-dark">{{ $book->penulis }}</span></p>
                        </div>
                        <div>
                            @if($book->stok > 0)
                                <span class="badge bg-success fs-6 rounded-pill px-3 py-2">
                                    <i class="bi bi-check2"></i> Tersedia: {{ $book->stok }} eks
                                </span>
                            @else
                                <span class="badge bg-danger fs-6 rounded-pill px-3 py-2">
                                    <i class="bi bi-x-circle"></i> Stok Habis
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Metadata Grid -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-sm-3">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Penerbit</small>
                            <span class="fw-semibold text-dark">{{ $book->penerbit }}</span>
                        </div>
                        <div class="col-6 col-sm-3">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Tahun Terbit</small>
                            <span class="fw-semibold text-dark">{{ $book->tahun_terbit }}</span>
                        </div>
                        <div class="col-6 col-sm-3">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Kategori</small>
                            <span class="fw-semibold text-dark">{{ $book->kategori }}</span>
                        </div>
                        <div class="col-6 col-sm-3">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Stok Tersedia</small>
                            <span class="fw-semibold text-dark">{{ $book->stok }} Eksemplar</span>
                        </div>
                    </div>

                    <!-- Sinopsis / Deskripsi -->
                    <div>
                        <h6 class="fw-bold text-dark mb-2">Sinopsis / Ringkasan Buku</h6>
                        <p class="text-secondary lh-lg mb-0">
                            {{ $book->deskripsi ?: 'Tidak ada sinopsis atau catatan deskripsi tambahan untuk buku ini.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Peminjaman Buku Ini -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-primary"></i> Riwayat Peminjaman Buku Ini
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa Peminjam</th>
                                <th>Tgl Pinjam</th>
                                <th>Tenggat</th>
                                <th>Status</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($book->borrowings as $b)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $b->member->nama ?? 'Siswa' }}</div>
                                        <small class="text-muted">{{ $b->member->kelas ?? '-' }}</small>
                                    </td>
                                    <td>{{ $b->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td>{{ $b->tanggal_kembali->format('d/m/Y') }}</td>
                                    <td>
                                        @if($b->status === 'Dipinjam')
                                            <span class="badge bg-warning text-dark rounded-pill">Dipinjam</span>
                                        @elseif($b->status === 'Dikembalikan')
                                            <span class="badge bg-success rounded-pill">Dikembalikan</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill">Terlambat</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $b->user->name ?? '-' }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada riwayat transaksi peminjaman untuk buku ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">Aksi Cepat</h6>
                    @if($book->stok > 0)
                        <a href="{{ route('borrowings.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-plus-circle"></i> Buat Peminjaman Buku Ini
                        </a>
                    @else
                        <button class="btn btn-secondary w-100 mb-2" disabled>
                            <i class="bi bi-x-circle"></i> Stok Habis (Tidak Dapat Dipinjam)
                        </button>
                    @endif
                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-pencil"></i> Edit Informasi Buku
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-2">Informasi Perpustakaan</h6>
                    <p class="small text-muted mb-1">Data ini tercatat resmi di database sistem informasi sekolah.</p>
                    <hr>
                    <small class="text-muted d-block">ID Buku: #{{ $book->id }}</small>
                    <small class="text-muted d-block">Terdaftar: {{ $book->created_at->format('d F Y, H:i') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
