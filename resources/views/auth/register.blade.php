<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun - Perpustakaan Digital</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="bg-light d-flex align-items-center min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <!-- Brand Header -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-4 shadow-sm p-3 mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-book-half fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Pendaftaran Pengguna Baru</h4>
                    <p class="text-muted small">Perpustakaan Digital Sekolah</p>
                </div>

                <!-- Card Form Registrasi -->
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-sm-5">
                        <div class="mb-4 text-center">
                            <h5 class="fw-bold text-dark mb-1">Buat Akun Anda</h5>
                            <p class="text-muted small">Lengkapi formulir untuk mendaftar sebagai Siswa atau Petugas</p>
                        </div>

                        <form action="{{ route('register.post') }}" method="POST">
                            @csrf

                            <!-- Pilihan Role -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Daftar Sebagai <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="role" id="roleSiswa" value="Siswa" {{ old('role', 'Siswa') === 'Siswa' ? 'checked' : '' }} onchange="toggleStudentFields()">
                                        <label class="btn btn-outline-primary w-100 py-2 small fw-semibold d-flex flex-column align-items-center gap-1" for="roleSiswa">
                                            <i class="bi bi-mortarboard fs-5"></i>
                                            <span>Siswa (Peminjam)</span>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="role" id="rolePetugas" value="Petugas" {{ old('role') === 'Petugas' ? 'checked' : '' }} onchange="toggleStudentFields()">
                                        <label class="btn btn-outline-secondary w-100 py-2 small fw-semibold d-flex flex-column align-items-center gap-1" for="rolePetugas">
                                            <i class="bi bi-person-badge fs-5"></i>
                                            <span>Petugas Staf</span>
                                        </label>
                                    </div>
                                </div>
                                @error('role')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Nama Lengkap -->
                            <div class="mb-3">
                                <label for="name" class="form-label small fw-semibold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           placeholder="Nama lengkap siswa atau petugas"
                                           required
                                           autofocus>
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kolom Khusus Siswa: NIS & Kelas -->
                            <div id="studentFields" class="row g-2 mb-3" style="display: {{ old('role', 'Siswa') === 'Siswa' ? 'flex' : 'none' }};">
                                <div class="col-6">
                                    <label for="nis" class="form-label small fw-semibold text-secondary">NIS Siswa <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('nis') is-invalid @enderror"
                                           id="nis"
                                           name="nis"
                                           value="{{ old('nis') }}"
                                           placeholder="Contoh: 20241010">
                                    @error('nis')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="kelas" class="form-label small fw-semibold text-secondary">Kelas <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('kelas') is-invalid @enderror"
                                           id="kelas"
                                           name="kelas"
                                           value="{{ old('kelas') }}"
                                           placeholder="Contoh: XII RPL 1">
                                    @error('kelas')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Form Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold text-secondary">Alamat Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email"
                                           class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="email@sekolah.sch.id"
                                           required>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label small fw-semibold text-secondary">Kata Sandi <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Minimal 6 karakter"
                                           required>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Konfirmasi Password -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Ulangi Kata Sandi <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-shield-lock"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control border-start-0 ps-0"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Ketik ulang kata sandi"
                                           required>
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary py-2 fw-semibold shadow-sm justify-content-center">
                                    <i class="bi bi-person-plus me-1"></i> Daftar Sekarang
                                </button>
                            </div>

                            <!-- Link ke Login -->
                            <div class="text-center">
                                <span class="small text-muted">Sudah memiliki akun?</span>
                                <a href="{{ route('login') }}" class="small fw-semibold text-decoration-none">
                                    Masuk di sini
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Footer Copyright -->
                <p class="text-center text-muted small mt-4">
                    &copy; {{ date('Y') }} Perpustakaan Digital Sekolah. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        function toggleStudentFields() {
            const roleSiswa = document.getElementById('roleSiswa');
            const studentFields = document.getElementById('studentFields');
            const nisInput = document.getElementById('nis');
            const kelasInput = document.getElementById('kelas');

            if (roleSiswa && roleSiswa.checked) {
                studentFields.style.display = 'flex';
                nisInput.required = true;
                kelasInput.required = true;
            } else {
                studentFields.style.display = 'none';
                nisInput.required = false;
                kelasInput.required = false;
            }
        }

        // Jalankan saat load
        document.addEventListener('DOMContentLoaded', toggleStudentFields);
    </script>
</body>
</html>
