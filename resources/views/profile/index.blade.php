@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Profil Pengguna</h3>
            <p class="text-muted mb-0">Informasi akun dan pengaturan data pribadi petugas perpustakaan.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- User Info Card -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="rounded-circle bg-primary text-white fw-bold mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 88px; height: 88px; font-size: 2.25rem;">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-2">{{ $user->email }}</p>
                <div class="mb-3">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                        <i class="bi bi-shield-check me-1"></i> {{ $user->role }}
                    </span>
                </div>

                <hr class="my-3">

                <div class="text-start small">
                    <div class="mb-2">
                        <span class="text-muted d-block fw-semibold">ID Pengguna:</span>
                        <span class="text-dark">#USER-{{ $user->id }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted d-block fw-semibold">Wewenang / Hak Akses:</span>
                        <span class="text-dark">{{ $user->role === 'Administrator' ? 'Akses Penuh Seluruh Sistem' : 'Petugas Sirkulasi & Pelayanan' }}</span>
                    </div>
                    <div>
                        <span class="text-muted d-block fw-semibold">Bergabung Sejak:</span>
                        <span class="text-dark">{{ $user->created_at->format('d F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile & Password Form -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-primary"></i> Perbarui Profil & Kata Sandi
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role (Read Only) -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Peran / Role</label>
                            <input type="text" class="form-control bg-light" value="{{ $user->role }}" readonly disabled>
                            <div class="form-text small">Role akun diatur oleh sistem sekolah.</div>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold text-dark mb-3">
                            <i class="bi bi-shield-lock text-primary"></i> Ganti Password (Opsional)
                        </h6>
                        <p class="small text-muted mb-3">Biarkan kolom di bawah ini kosong jika Anda tidak ingin mengubah kata sandi.</p>

                        <!-- Password Saat Ini -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                            <input type="password"
                                   name="current_password"
                                   id="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="Masukkan password saat ini jika ingin ganti password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- Password Baru -->
                            <div class="col-12 col-md-6">
                                <label for="new_password" class="form-label fw-semibold">Password Baru</label>
                                <input type="password"
                                       name="new_password"
                                       id="new_password"
                                       class="form-control @error('new_password') is-invalid @enderror"
                                       placeholder="Minimal 6 karakter">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password Baru -->
                            <div class="col-12 col-md-6">
                                <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <input type="password"
                                       name="new_password_confirmation"
                                       id="new_password_confirmation"
                                       class="form-control"
                                       placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
