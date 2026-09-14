@extends('layouts.app')

@section('title', 'Detail Anggota - ' . $member->nama)

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('members.index') }}" class="btn btn-light border shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h3 class="fw-bold text-dark mb-0">Kartu Profil Anggota</h3>
                <small class="text-muted">NIS: {{ $member->nis }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('members.edit', $member->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Edit Data
            </a>
            <button type="button"
                    class="btn btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteConfirmModal"
                    data-action="{{ route('members.destroy', $member->id) }}"
                    data-name="{{ $member->nama }}">
                <i class="bi bi-trash"></i> Hapus
            </button>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Main Info Card -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm text-center p-4 mb-4">
                <div class="rounded-circle bg-primary-subtle text-primary fw-bold mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr($member->nama, 0, 2)) }}
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $member->nama }}</h5>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill mx-auto mb-3">
                    {{ $member->kelas }}
                </span>
                <p class="text-muted small mb-3">
                    <i class="bi bi-card-text me-1"></i> NIS: <strong class="text-dark">{{ $member->nis }}</strong>
                </p>

                <hr class="my-3">

                <div class="text-start small">
                    <div class="mb-2">
                        <span class="text-muted d-block fw-semibold">Nomor WhatsApp / HP:</span>
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $member->nomor_telepon) }}" target="_blank" class="text-success text-decoration-none fw-semibold">
                            <i class="bi bi-whatsapp me-1"></i> {{ $member->nomor_telepon }}
                        </a>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted d-block fw-semibold">Alamat Email:</span>
                        <span class="text-dark">{{ $member->email ?: 'Tidak didaftarkan' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted d-block fw-semibold">Alamat Rumah:</span>
                        <span class="text-dark">{{ $member->alamat }}</span>
                    </div>
                    <div>
                        <span class="text-muted d-block fw-semibold">Terdaftar Sejak:</span>
                        <span class="text-dark">{{ $member->created_at->format('d F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrowing History Card -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-journal-check text-primary"></i> Riwayat Peminjaman Buku Siswa
                    </h6>
                    <a href="{{ route('borrowings.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Buat Peminjaman
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Judul Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tenggat Kembali</th>
                                <th>Tgl Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($member->borrowings as $b)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $b->book->judul ?? 'Buku Dihapus' }}</div>
                                        <small class="text-muted">{{ $b->book->kategori ?? '-' }}</small>
                                    </td>
                                    <td>{{ $b->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td>{{ $b->tanggal_kembali->format('d/m/Y') }}</td>
                                    <td>
                                        {{ $b->tanggal_pengembalian_aktual ? $b->tanggal_pengembalian_aktual->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        @if($b->status === 'Dipinjam')
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                                Dipinjam
                                            </span>
                                        @elseif($b->status === 'Dikembalikan')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                                Dikembalikan
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">
                                                Terlambat
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Siswa ini belum memiliki riwayat peminjaman buku.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
