@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">{{ Auth::user()->role === 'Siswa' ? 'Riwayat Peminjaman Saya' : 'Transaksi Peminjaman Buku' }}</h3>
            <p class="text-muted mb-0">{{ Auth::user()->role === 'Siswa' ? 'Pantau buku yang sedang dan pernah kamu pinjam dari perpustakaan.' : 'Catat dan pantau sirkulasi peminjaman serta pengembalian buku sekolah.' }}</p>
        </div>
        @if(Auth::user()->role !== 'Siswa')
            <div>
                <a href="{{ route('borrowings.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle"></i> Catat Peminjaman Baru
                </a>
            </div>
        @endif
    </div>

    <!-- Filter & Status Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <!-- Status Filter Badges/Pills -->
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('borrowings.index', array_merge(request()->except('status', 'page'), [])) }}"
                       class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-light border' }}">
                        Semua Status
                    </a>
                    <a href="{{ route('borrowings.index', array_merge(request()->except('status', 'page'), ['status' => 'Dipinjam'])) }}"
                       class="btn btn-sm {{ request('status') === 'Dipinjam' ? 'btn-warning' : 'btn-light border' }}">
                        <i class="bi bi-hourglass-top"></i> Dipinjam
                    </a>
                    <a href="{{ route('borrowings.index', array_merge(request()->except('status', 'page'), ['status' => 'Terlambat'])) }}"
                       class="btn btn-sm {{ request('status') === 'Terlambat' ? 'btn-danger' : 'btn-light border' }}">
                        <i class="bi bi-exclamation-octagon"></i> Terlambat
                    </a>
                    <a href="{{ route('borrowings.index', array_merge(request()->except('status', 'page'), ['status' => 'Dikembalikan'])) }}"
                       class="btn btn-sm {{ request('status') === 'Dikembalikan' ? 'btn-success' : 'btn-light border' }}">
                        <i class="bi bi-check2-circle"></i> Dikembalikan
                    </a>
                </div>

                <!-- Search Box -->
                <form action="{{ route('borrowings.index') }}" method="GET" class="d-flex gap-2">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <div class="input-group input-group-sm">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Cari siswa atau judul buku..."
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-secondary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('borrowings.index', request()->except('search')) }}" class="btn btn-sm btn-outline-secondary" title="Reset Search">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Siswa Peminjam</th>
                        <th>Buku Yang Dipinjam</th>
                        <th>Tgl Pinjam</th>
                        <th>Tenggat Kembali</th>
                        <th>Status</th>
                        <th>Petugas</th>
                        <th class="text-end" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings as $index => $b)
                        <tr>
                            <td class="text-muted fw-semibold">
                                {{ $borrowings->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="fw-bold text-dark">
                                    <a href="{{ route('members.show', $b->member_id) }}" class="text-decoration-none text-dark">
                                        {{ $b->member->nama ?? 'Siswa Dihapus' }}
                                    </a>
                                </div>
                                <small class="text-muted">{{ $b->member->kelas ?? '-' }} • NIS: {{ $b->member->nis ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark text-truncate" style="max-width: 200px;">
                                    <a href="{{ route('books.show', $b->book_id) }}" class="text-decoration-none text-dark">
                                        {{ $b->book->judul ?? 'Buku Dihapus' }}
                                    </a>
                                </div>
                                <small class="text-muted">{{ $b->book->kategori ?? '-' }}</small>
                            </td>
                            <td>{{ $b->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>
                                <span class="{{ $b->status === 'Terlambat' ? 'text-danger fw-bold' : '' }}">
                                    {{ $b->tanggal_kembali->format('d/m/Y') }}
                                </span>
                                @if($b->status === 'Dikembalikan' && $b->tanggal_pengembalian_aktual)
                                    <br><small class="text-success"><i class="bi bi-check2"></i> Kembali: {{ $b->tanggal_pengembalian_aktual->format('d/m/Y') }}</small>
                                @endif
                            </td>
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
                            <td>
                                <small class="text-muted">{{ $b->user->name ?? 'Petugas' }}</small>
                            </td>
                            <td class="text-end">
                                @if(Auth::user()->role === 'Siswa')
                                    <a href="{{ route('borrowings.show', $b->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                @else
                                    <div class="btn-group btn-group-sm">
                                        <!-- Tombol Kembalikan Buku Cepat -->
                                        @if($b->status !== 'Dikembalikan')
                                            <form action="{{ route('borrowings.return', $b->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm rounded-start" title="Kembalikan Buku Ini">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Kembalikan
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('borrowings.show', $b->id) }}" class="btn btn-outline-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('borrowings.edit', $b->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal"
                                                data-action="{{ route('borrowings.destroy', $b->id) }}"
                                                data-name="transaksi peminjaman #{{ $b->id }}"
                                                title="Hapus Transaksi">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
                                <h6>Tidak ada data transaksi peminjaman.</h6>
                                <p class="small mb-0">Klik tombol "Catat Peminjaman Baru" untuk memulai transaksi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($borrowings->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan <strong>{{ $borrowings->firstItem() }}</strong> sampai <strong>{{ $borrowings->lastItem() }}</strong> dari <strong>{{ $borrowings->total() }}</strong> transaksi
                </small>
                <div>
                    {{ $borrowings->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
