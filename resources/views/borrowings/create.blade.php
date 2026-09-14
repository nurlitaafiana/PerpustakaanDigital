@extends('layouts.app')

@section('title', 'Catat Peminjaman Baru')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('borrowings.index') }}" class="btn btn-light border shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold text-dark mb-0">Catat Peminjaman Buku</h3>
            <small class="text-muted">Formulir sirkulasi peminjaman buku untuk siswa perpustakaan</small>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('borrowings.store') }}" method="POST">
                        @csrf

                        <!-- Pilih Anggota -->
                        <div class="mb-3">
                            <label for="member_id" class="form-label fw-semibold">Pilih Siswa Peminjam <span class="text-danger">*</span></label>
                            <select name="member_id" id="member_id" class="form-select @error('member_id') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Cari dan Pilih Siswa --</option>
                                @foreach($members as $m)
                                    <option value="{{ $m->id }}" {{ old('member_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama }} (NIS: {{ $m->nis }} - {{ $m->kelas }})
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text small">
                                Siswa belum terdaftar? <a href="{{ route('members.create') }}">Tambah anggota baru di sini</a>.
                            </div>
                        </div>

                        <!-- Pilih Buku -->
                        <div class="mb-3">
                            <label for="book_id" class="form-label fw-semibold">Pilih Buku Yang Dipinjam <span class="text-danger">*</span></label>
                            <select name="book_id" id="book_id" class="form-select @error('book_id') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Buku (Tersedia di Rak) --</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                        {{ $book->judul }} — Stok Tersedia: [{{ $book->stok }}] (Kategori: {{ $book->kategori }})
                                    </option>
                                @endforeach
                            </select>
                            @error('book_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <!-- Tanggal Pinjam -->
                            <div class="col-12 col-md-6">
                                <label for="tanggal_pinjam" class="form-label fw-semibold">Tanggal Peminjaman <span class="text-danger">*</span></label>
                                <input type="date"
                                       name="tanggal_pinjam"
                                       id="tanggal_pinjam"
                                       class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                       value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                                       required>
                                @error('tanggal_pinjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Kembali (Batas Tenggat) -->
                            <div class="col-12 col-md-6">
                                <label for="tanggal_kembali" class="form-label fw-semibold">Batas Tanggal Pengembalian <span class="text-danger">*</span></label>
                                <input type="date"
                                       name="tanggal_kembali"
                                       id="tanggal_kembali"
                                       class="form-control @error('tanggal_kembali') is-invalid @enderror"
                                       value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+7 days'))) }}"
                                       required>
                                @error('tanggal_kembali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text small">Standar durasi peminjaman sekolah adalah 7 hari.</div>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label for="catatan" class="form-label fw-semibold">Catatan / Keterangan (Opsional)</label>
                            <textarea name="catatan"
                                      id="catatan"
                                      rows="3"
                                      class="form-control @error('catatan') is-invalid @enderror"
                                      placeholder="Contoh: Keperluan tugas kelompok Bahasa Indonesia...">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('borrowings.index') }}" class="btn btn-light border">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2-circle"></i> Simpan Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-primary"></i> Ketentuan Peminjaman
                    </h6>
                    <ul class="small text-muted ps-3 mb-0">
                        <li class="mb-2">Menyimpan peminjaman akan <strong>secara otomatis mengurangi stok fisik buku sebanyak 1</strong>.</li>
                        <li class="mb-2">Buku dengan stok 0 tidak akan ditampilkan pada daftar pilihan.</li>
                        <li class="mb-2">Jika tanggal batas kembali terlampaui dan belum dikembalikan, sistem otomatis menandai transaksi berstatus <strong>Terlambat</strong>.</li>
                        <li>Ketika buku dikembalikan, stok buku akan <strong>otomatis bertambah kembali</strong>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
