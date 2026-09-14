@extends('layouts.app')

@section('title', 'Edit Buku - ' . $book->judul)

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('books.index') }}" class="btn btn-light border shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold text-dark mb-0">Edit Data Buku</h3>
            <small class="text-muted">Perbarui rincian katalog dan stok untuk buku: <strong>{{ $book->judul }}</strong></small>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('books.update', $book->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Judul Buku -->
                        <div class="mb-3">
                            <label for="judul" class="form-label fw-semibold">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="judul"
                                   id="judul"
                                   class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul', $book->judul) }}"
                                   required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <!-- Penulis -->
                            <div class="col-12 col-md-6">
                                <label for="penulis" class="form-label fw-semibold">Penulis / Pengarang <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="penulis"
                                       id="penulis"
                                       class="form-control @error('penulis') is-invalid @enderror"
                                       value="{{ old('penulis', $book->penulis) }}"
                                       required>
                                @error('penulis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Penerbit -->
                            <div class="col-12 col-md-6">
                                <label for="penerbit" class="form-label fw-semibold">Penerbit <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="penerbit"
                                       id="penerbit"
                                       class="form-control @error('penerbit') is-invalid @enderror"
                                       value="{{ old('penerbit', $book->penerbit) }}"
                                       required>
                                @error('penerbit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <!-- Tahun Terbit -->
                            <div class="col-12 col-sm-4">
                                <label for="tahun_terbit" class="form-label fw-semibold">Tahun Terbit <span class="text-danger">*</span></label>
                                <input type="number"
                                       name="tahun_terbit"
                                       id="tahun_terbit"
                                       class="form-control @error('tahun_terbit') is-invalid @enderror"
                                       value="{{ old('tahun_terbit', $book->tahun_terbit) }}"
                                       min="1900"
                                       max="{{ date('Y') + 1 }}"
                                       required>
                                @error('tahun_terbit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="col-12 col-sm-4">
                                <label for="kategori" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori" id="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                    @foreach($kategoriList as $kategori)
                                        <option value="{{ $kategori }}" {{ old('kategori', $book->kategori) == $kategori ? 'selected' : '' }}>
                                            {{ $kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stok Buku -->
                            <div class="col-12 col-sm-4">
                                <label for="stok" class="form-label fw-semibold">Jumlah Stok Fisik <span class="text-danger">*</span></label>
                                <input type="number"
                                       name="stok"
                                       id="stok"
                                       class="form-control @error('stok') is-invalid @enderror"
                                       value="{{ old('stok', $book->stok) }}"
                                       min="0"
                                       required>
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Deskripsi / Sinopsis -->
                        <div class="mb-4">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi / Sinopsis Buku</label>
                            <textarea name="deskripsi"
                                      id="deskripsi"
                                      rows="4"
                                      class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $book->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button"
                                    class="btn btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteConfirmModal"
                                    data-action="{{ route('books.destroy', $book->id) }}"
                                    data-name="{{ $book->judul }}">
                                <i class="bi bi-trash"></i> Hapus Buku Ini
                            </button>

                            <div class="d-flex gap-2">
                                <a href="{{ route('books.index') }}" class="btn btn-light border">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Perbarui Buku
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">Informasi Sistem</h6>
                    <p class="small text-muted mb-2">
                        <strong>ID Buku:</strong> #{{ $book->id }}
                    </p>
                    <p class="small text-muted mb-2">
                        <strong>Dibuat Pada:</strong> {{ $book->created_at->format('d M Y, H:i') }}
                    </p>
                    <p class="small text-muted mb-3">
                        <strong>Pembaruan Terakhir:</strong> {{ $book->updated_at->format('d M Y, H:i') }}
                    </p>
                    <div class="alert alert-warning small mb-0">
                        <i class="bi bi-exclamation-circle me-1"></i> Perubahan stok buku langsung memengaruhi ketersediaan buku untuk dipinjam oleh siswa.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
