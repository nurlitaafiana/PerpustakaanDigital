@extends('layouts.app')

@section('title', 'Edit Anggota - ' . $member->nama)

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('members.index') }}" class="btn btn-light border shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold text-dark mb-0">Edit Data Anggota</h3>
            <small class="text-muted">Perbarui informasi profil siswa: <strong>{{ $member->nama }}</strong></small>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('members.update', $member->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama"
                                   id="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $member->nama) }}"
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
                                       value="{{ old('nis', $member->nis) }}"
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
                                       value="{{ old('kelas', $member->kelas) }}"
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
                                       value="{{ old('email', $member->email) }}">
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
                                       value="{{ old('nomor_telepon', $member->nomor_telepon) }}"
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
                                      required>{{ old('alamat', $member->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button"
                                    class="btn btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteConfirmModal"
                                    data-action="{{ route('members.destroy', $member->id) }}"
                                    data-name="{{ $member->nama }}">
                                <i class="bi bi-trash"></i> Hapus Siswa
                            </button>

                            <div class="d-flex gap-2">
                                <a href="{{ route('members.index') }}" class="btn btn-light border">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Perbarui Data
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
                    <h6 class="fw-bold text-dark mb-3">Rincian Anggota</h6>
                    <p class="small text-muted mb-2"><strong>ID:</strong> #{{ $member->id }}</p>
                    <p class="small text-muted mb-2"><strong>Terdaftar:</strong> {{ $member->created_at->format('d M Y') }}</p>
                    <p class="small text-muted mb-3"><strong>Total Transaksi:</strong> {{ $member->borrowings()->count() }} kali meminjam</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
