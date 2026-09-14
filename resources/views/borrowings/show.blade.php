@extends('layouts.app')

@section('title', 'Detail Peminjaman #' . $borrowing->id)

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('borrowings.index') }}" class="btn btn-light border shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h3 class="fw-bold text-dark mb-0">Rincian Transaksi Peminjaman</h3>
                <small class="text-muted">Kode Transaksi: #TRX-{{ str_pad($borrowing->id, 5, '0', STR_PAD_LEFT) }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            @if($borrowing->status !== 'Dikembalikan')
                <form action="{{ route('borrowings.return', $borrowing->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-arrow-counterclockwise"></i> Kembalikan Buku
                    </button>
                </form>
            @endif
            <a href="{{ route('borrowings.edit', $borrowing->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <button type="button"
                    class="btn btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteConfirmModal"
                    data-action="{{ route('borrowings.destroy', $borrowing->id) }}"
                    data-name="transaksi peminjaman #{{ $borrowing->id }}">
                <i class="bi bi-trash"></i> Hapus
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Card -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <!-- Status Banner -->
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 mb-4 {{ $borrowing->status === 'Dikembalikan' ? 'bg-success-subtle text-success' : ($borrowing->status === 'Terlambat' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning-emphasis') }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi {{ $borrowing->status === 'Dikembalikan' ? 'bi-check-circle-fill' : ($borrowing->status === 'Terlambat' ? 'bi-exclamation-octagon-fill' : 'bi-hourglass-split') }} fs-4"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Status: {{ $borrowing->status }}</h6>
                                <small>
                                    @if($borrowing->status === 'Dikembalikan')
                                        Buku telah dikembalikan pada {{ $borrowing->tanggal_pengembalian_aktual ? $borrowing->tanggal_pengembalian_aktual->format('d F Y') : '-' }}.
                                    @elseif($borrowing->status === 'Terlambat')
                                        Tenggat waktu pengembalian telah terlampaui. Segera hubungi siswa.
                                    @else
                                        Masa peminjaman sedang aktif. Tenggat kembali: {{ $borrowing->tanggal_kembali->format('d F Y') }}.
                                    @endif
                                </small>
                            </div>
                        </div>
                        <span class="badge {{ $borrowing->status === 'Dikembalikan' ? 'bg-success' : ($borrowing->status === 'Terlambat' ? 'bg-danger' : 'bg-warning text-dark') }} fs-6 rounded-pill px-3 py-2">
                            {{ $borrowing->status }}
                        </span>
                    </div>

                    <!-- Details Table -->
                    <div class="row g-4">
                        <!-- Siswa Info -->
                        <div class="col-12 col-md-6">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bi bi-person text-primary"></i> Data Peminjam
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 120px;">Nama Siswa</td>
                                    <td>: <strong>{{ $borrowing->member->nama ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">NIS</td>
                                    <td>: {{ $borrowing->member->nis ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kelas</td>
                                    <td>: {{ $borrowing->member->kelas ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. Telepon</td>
                                    <td>: {{ $borrowing->member->nomor_telepon ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Buku Info -->
                        <div class="col-12 col-md-6">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bi bi-book text-primary"></i> Data Buku
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 120px;">Judul Buku</td>
                                    <td>: <strong>{{ $borrowing->book->judul ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Penulis</td>
                                    <td>: {{ $borrowing->book->penulis ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kategori</td>
                                    <td>: {{ $borrowing->book->kategori ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Penerbit</td>
                                    <td>: {{ $borrowing->book->penerbit ?? '-' }} ({{ $borrowing->book->tahun_terbit ?? '-' }})</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Waktu Peminjaman -->
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="bi bi-calendar-check text-primary"></i> Kronologi Peminjaman
                    </h6>
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-4">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Tanggal Pinjam</small>
                                <span class="fw-bold text-dark fs-6">{{ $borrowing->tanggal_pinjam->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Batas Pengembalian</small>
                                <span class="fw-bold text-dark fs-6">{{ $borrowing->tanggal_kembali->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Tanggal Selesai</small>
                                <span class="fw-bold text-dark fs-6">
                                    {{ $borrowing->tanggal_pengembalian_aktual ? $borrowing->tanggal_pengembalian_aktual->format('d M Y') : 'Belum Kembali' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($borrowing->catatan)
                        <div class="mt-3">
                            <h6 class="fw-bold text-dark mb-1">Catatan Tambahan:</h6>
                            <p class="text-secondary small mb-0 bg-light p-3 rounded-3">{{ $borrowing->catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">Petugas Bertugas</h6>
                    <p class="small text-muted mb-2">
                        <strong>Nama Petugas:</strong> {{ $borrowing->user->name ?? 'Petugas Perpustakaan' }}
                    </p>
                    <p class="small text-muted mb-2">
                        <strong>Email:</strong> {{ $borrowing->user->email ?? '-' }}
                    </p>
                    <p class="small text-muted mb-0">
                        <strong>Waktu Input Sistem:</strong> {{ $borrowing->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
