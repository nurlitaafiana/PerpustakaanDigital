@extends('layouts.app')

@section('title', 'Tambah Anggota Baru')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('members.index') }}" class="btn btn-light border shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold text-dark mb-0">Tambah Anggota Siswa</h3>
            <small class="text-muted">Daftarkan siswa baru sebagai anggota peminjam perpustakaan</small>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('members.store') }}" method="POST">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama"
                                   id="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama') }}"
                                   placeholder="Contoh: Ahmad Fauzi"
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <!-- NIS -->
                            <div class="col-12 col-md-6">
                                <label for="nis" class="form-label fw-semibold">Nomor Induk Siswa (NIS) <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="nis"
                                       id="nis"
                                       class="form-control @error('nis') is-invalid @enderror"
                                       value="{{ old('nis') }}"
                                       placeholder="Contoh: 20241009"
                                       required>
                                @error('nis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kelas -->
                            <div class="col-12 col-md-6">
                                <label for="kelas" class="form-label fw-semibold">Kelas / Jurusan <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="kelas"
                                       id="kelas"
                                       class="form-control @error('kelas') is-invalid @enderror"
                                       value="{{ old('kelas') }}"
                                       placeholder="Contoh: XII RPL 1 atau XI MIPA 2"
                                       required>
                                @error('kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <!-- Email -->
                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label fw-semibold">Alamat Email (Opsional)</label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       placeholder="siswa@sekolah.sch.id">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div class="col-12 col-md-6">
                                <label for="nomor_telepon" class="form-label fw-semibold">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="nomor_telepon"
                                       id="nomor_telepon"
                                       class="form-control @error('nomor_telepon') is-invalid @enderror"
                                       value="{{ old('nomor_telepon') }}"
                                       placeholder="Contoh: 081234567890"
                                       required>
                                @error('nomor_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-4">
                            <label for="alamat" class="form-label fw-semibold">Alamat Tempat Tinggal <span class="text-danger">*</span></label>
                            <textarea name="alamat"
                                      id="alamat"
                                      rows="3"
                                      class="form-control @error('alamat') is-invalid @enderror"
                                      placeholder="Masukkan alamat lengkap siswa..."
                                      required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('members.index') }}" class="btn btn-light border">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-check"></i> Simpan Anggota
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
                        <i class="bi bi-card-checklist text-primary"></i> Ketentuan Keanggotaan
                    </h6>
                    <ul class="small text-muted ps-3 mb-0">
                        <li class="mb-2">NIS harus unik dan sesuai dengan data kesiswaan sekolah.</li>
                        <li class="mb-2">Nomor telepon / WhatsApp digunakan untuk notifikasi pengingat pengembalian buku.</li>
                        <li>Siswa yang terdaftar dapat langsung meminjam buku melalui menu Peminjaman.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
