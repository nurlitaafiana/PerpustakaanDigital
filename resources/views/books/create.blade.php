@extends('layouts.app')

@section('title', 'Tambah Buku Baru')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('books.index') }}" class="btn btn-light border shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold text-dark mb-0">Tambah Buku Baru</h3>
            <small class="text-muted">Masukkan data buku untuk didaftarkan ke katalog perpustakaan</small>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('books.store') }}" method="POST">
                        @csrf

                        <!-- Judul Buku -->
                        <div class="mb-3">
                            <label for="judul" class="form-label fw-semibold">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="judul"
                                   id="judul"
                                   class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul') }}"
                                   placeholder="Contoh: Laskar Pelangi"
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
                                       value="{{ old('penulis') }}"
                                       placeholder="Contoh: Andrea Hirata"
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
                                       value="{{ old('penerbit') }}"
                                       placeholder="Contoh: Bentang Pustaka"
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
                                       value="{{ old('tahun_terbit', date('Y')) }}"
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
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach($kategoriList as $kategori)
                                        <option value="{{ $kategori }}" {{ old('kategori') == $kategori ? 'selected' : '' }}>
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
                                       value="{{ old('stok', 1) }}"
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
                                      class="form-control @error('deskripsi') is-invalid @enderror"
                                      placeholder="Tuliskan ringkasan singkat mengenai buku ini...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('books.index') }}" class="btn btn-light border">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Buku
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-primary"></i> Panduan Pengisian
                    </h6>
                    <ul class="small text-muted ps-3 mb-0">
                        <li class="mb-2">Pastikan judul dan pengarang diisi dengan ejaan yang tepat untuk mempermudah pencarian.</li>
                        <li class="mb-2">Stok buku mencerminkan jumlah eksemplar fisik yang tersedia di rak perpustakaan.</li>
                        <li class="mb-2">Buku yang memiliki stok minimal 1 dapat langsung dipinjamkan melalui modul peminjaman.</li>
                        <li>Kategori membantu siswa memfilter buku sesuai minat baca.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
