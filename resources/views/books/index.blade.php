@extends('layouts.app')

@section('title', 'Data Buku')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">{{ Auth::user()->role === 'Siswa' ? 'Katalog Koleksi Buku' : 'Daftar Koleksi Buku' }}</h3>
            <p class="text-muted mb-0">{{ Auth::user()->role === 'Siswa' ? 'Jelajahi berbagai buku pelajaran, ensiklopedia, dan novel perpustakaan.' : 'Kelola katalog dan inventaris buku perpustakaan sekolah.' }}</p>
        </div>
        @if(Auth::user()->role !== 'Siswa')
            <div>
                <a href="{{ route('books.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Buku Baru
                </a>
            </div>
        @endif
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('books.index') }}" method="GET" class="row g-2 align-items-center">
                <!-- Search Box -->
                <div class="col-12 col-md-5 col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                               name="search"
                               class="form-control border-start-0 ps-0"
                               placeholder="Cari berdasarkan judul, penulis, atau penerbit..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                    <select name="kategori" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($daftarKategori as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    @if(request('search') || request('kategori'))
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Penerbit & Tahun</th>
                        <th>Kategori</th>
                        <th class="text-center">Stok</th>
                        <th class="text-end" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $index => $book)
                        <tr>
                            <td class="text-muted fw-semibold">
                                {{ $books->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="fw-bold text-dark">
                                    <a href="{{ route('books.show', $book->id) }}" class="text-decoration-none text-dark hover-primary">
                                        {{ $book->judul }}
                                    </a>
                                </div>
                                @if($book->deskripsi)
                                    <small class="text-muted text-truncate d-block" style="max-width: 280px;">
                                        {{ Str::limit($book->deskripsi, 60) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="text-secondary">{{ $book->penulis }}</span>
                            </td>
                            <td>
                                <div>{{ $book->penerbit }}</div>
                                <small class="text-muted">{{ $book->tahun_terbit }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">
                                    {{ $book->kategori }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($book->stok > 3)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                        {{ $book->stok }} eks
                                    </span>
                                @elseif($book->stok > 0)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-1">
                                        {{ $book->stok }} eks
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">
                                        Habis
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if(Auth::user()->role === 'Siswa')
                                    <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                @else
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-outline-info" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-outline-warning" title="Edit Buku">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal"
                                                data-action="{{ route('books.destroy', $book->id) }}"
                                                data-name="{{ $book->judul }}"
                                                title="Hapus Buku">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-search fs-1 d-block mb-2 text-secondary"></i>
                                <h6>Tidak ada data buku ditemukan.</h6>
                                <p class="small mb-0">Coba ubah kata kunci pencarian atau tambah data baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($books->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan <strong>{{ $books->firstItem() }}</strong> sampai <strong>{{ $books->lastItem() }}</strong> dari <strong>{{ $books->total() }}</strong> buku
                </small>
                <div>
                    {{ $books->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
