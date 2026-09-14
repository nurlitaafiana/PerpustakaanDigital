@extends('layouts.app')

@section('title', 'Data Anggota')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Daftar Anggota Perpustakaan</h3>
            <p class="text-muted mb-0">Kelola data siswa yang terdaftar sebagai anggota peminjam.</p>
        </div>
        <div>
            <a href="{{ route('members.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-person-plus"></i> Tambah Anggota Baru
            </a>
        </div>
    </div>

    <!-- Search Toolbar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('members.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                               name="search"
                               class="form-control border-start-0 ps-0"
                               placeholder="Cari berdasarkan nama siswa, NIS, atau kelas..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="bi bi-search"></i> Cari Siswa
                    </button>
                    @if(request('search'))
                        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary" title="Reset">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Members Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Kontak / No. HP</th>
                        <th>Alamat</th>
                        <th class="text-end" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $index => $member)
                        <tr>
                            <td class="text-muted fw-semibold">
                                {{ $members->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.85rem;">
                                        {{ strtoupper(substr($member->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('members.show', $member->id) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $member->nama }}
                                        </a>
                                        @if($member->email)
                                            <small class="text-muted d-block">{{ $member->email }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    {{ $member->nis }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill">
                                    {{ $member->kelas }}
                                </span>
                            </td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $member->nomor_telepon) }}" target="_blank" class="text-decoration-none text-success small">
                                    <i class="bi bi-whatsapp me-1"></i> {{ $member->nomor_telepon }}
                                </a>
                            </td>
                            <td>
                                <small class="text-muted text-truncate d-block" style="max-width: 200px;">
                                    {{ $member->alamat }}
                                </small>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('members.show', $member->id) }}" class="btn btn-outline-info" title="Detail Siswa">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('members.edit', $member->id) }}" class="btn btn-outline-warning" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteConfirmModal"
                                            data-action="{{ route('members.destroy', $member->id) }}"
                                            data-name="{{ $member->nama }}"
                                            title="Hapus Anggota">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                                <h6>Tidak ada data anggota ditemukan.</h6>
                                <p class="small mb-0">Coba ubah kata kunci pencarian atau daftarkan anggota baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan <strong>{{ $members->firstItem() }}</strong> sampai <strong>{{ $members->lastItem() }}</strong> dari <strong>{{ $members->total() }}</strong> anggota
                </small>
                <div>
                    {{ $members->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
