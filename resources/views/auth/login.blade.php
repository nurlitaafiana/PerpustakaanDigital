<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan Digital</title>

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
            <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
                <!-- Brand Header -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-4 shadow-sm p-3 mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-book-half fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Perpustakaan Digital</h4>
                    <p class="text-muted small">Sistem Informasi Perpustakaan Sekolah Modern</p>
                </div>

                <!-- Card Form Login -->
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-sm-5">
                        <div class="mb-4 text-center">
                            <h5 class="fw-bold text-dark mb-1">Masuk ke Akun Anda</h5>
                            <p class="text-muted small">Silakan masukkan email dan password petugas</p>
                        </div>

                        <!-- Flash Alerts -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 small py-2" role="alert">
                                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show border-0 small py-2" role="alert">
                                <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->has('email'))
                            <div class="alert alert-danger border-0 small py-2" role="alert">
                                <i class="bi bi-shield-x me-1"></i> {{ $errors->first('email') }}
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <!-- Form Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold text-secondary">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email"
                                           class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', 'admin@perpustakaan.sch.id') }}"
                                           placeholder="nama@perpustakaan.sch.id"
                                           required
                                           autofocus>
                                </div>
                            </div>

                            <!-- Form Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label small fw-semibold text-secondary">Kata Sandi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           value="password"
                                           placeholder="••••••••"
                                           required>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" checked>
                                <label class="form-check-label small text-muted" for="remember">Ingat saya di perangkat ini</label>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary py-2 fw-semibold shadow-sm justify-content-center">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                                </button>
                            </div>

                            <!-- Link ke Registrasi -->
                            <div class="text-center">
                                <span class="small text-muted">Belum memiliki akun?</span>
                                <a href="{{ route('register') }}" class="small fw-semibold text-decoration-none">
                                    Daftar di sini
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Demo Credentials Helper Footer -->
                    <div class="card-footer bg-light border-0 rounded-bottom-4 p-3 text-center">
                        <small class="text-muted d-block fw-semibold mb-2">
                            <i class="bi bi-info-circle text-primary me-1"></i> Akun Demo Cepat:
                        </small>
                        <div class="d-flex flex-wrap justify-content-center gap-2 small">
                            <span class="badge bg-white text-dark border">
                                <strong>Admin:</strong> admin@perpustakaan.sch.id (pass: password)
                            </span>
                            <span class="badge bg-white text-dark border">
                                <strong>Petugas:</strong> petugas@perpustakaan.sch.id (pass: password)
                            </span>
                            <span class="badge bg-primary text-white border-0 shadow-sm">
                                <strong>Siswa (User):</strong> alfiana@perpustakaan.sch.id (pass: 12345678)
                            </span>
                        </div>
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
</body>
</html>
