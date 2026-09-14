<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard') - Perpustakaan Digital</title>

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
<body>
    <div class="d-flex min-vh-100">
        <!-- Sidebar Backdrop for Mobile -->
        <div id="sidebarBackdrop" class="sidebar-backdrop"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="d-flex flex-column p-3 text-white">
            <!-- Brand -->
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 mb-4 text-white text-decoration-none px-2 pt-2">
                <div class="bg-primary rounded-3 p-2 d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 40px; height: 40px;">
                    <i class="bi bi-book-half fs-4"></i>
                </div>
                <div>
                    <span class="fs-5 fw-bold d-block text-white lh-1">Perpustakaan</span>
                    <small class="text-secondary fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">DIGITAL SEKOLAH</small>
                </div>
            </a>

            <!-- Navigation Menu -->
            <div class="sidebar-heading">Menu Utama</div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>{{ Auth::user()->role === 'Siswa' ? 'Dashboard Siswa' : 'Dashboard' }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('books.index') }}" class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <span>{{ Auth::user()->role === 'Siswa' ? 'Katalog Buku' : 'Data Buku' }}</span>
                    </a>
                </li>
                @if(Auth::user()->role !== 'Siswa')
                    <li class="nav-item">
                        <a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill"></i>
                            <span>Data Anggota</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('borrowings.index') }}" class="nav-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>{{ Auth::user()->role === 'Siswa' ? 'Peminjaman Saya' : 'Peminjaman' }}</span>
                    </a>
                </li>

                <div class="sidebar-heading mt-3">Akun & Pengaturan</div>
                <li class="nav-item">
                    <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>Profil Saya</span>
                    </a>
                </li>
            </ul>

            <hr class="border-secondary opacity-25">

            <!-- User Info in Sidebar Footer -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle p-2 rounded hover-bg" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; font-size: 0.9rem;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="overflow-hidden me-auto text-truncate" style="max-width: 130px;">
                        <strong class="d-block text-truncate small">{{ Auth::user()->name ?? 'Pengguna' }}</strong>
                        <span class="badge bg-secondary-subtle text-secondary-emphasis" style="font-size: 0.65rem;">{{ Auth::user()->role ?? 'Petugas' }}</span>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <div id="main-content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg top-navbar sticky-top">
                <div class="container-fluid px-0">
                    <button id="sidebarToggle" class="btn btn-outline-secondary btn-sm d-lg-none me-2" type="button">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small d-none d-sm-inline">
                            <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                        </span>
                    </div>

                    <div class="ms-auto d-flex align-items-center gap-3">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 d-none d-md-inline-flex align-items-center gap-1">
                            <i class="bi bi-shield-check"></i> {{ Auth::user()->role ?? 'Petugas' }}
                        </span>

                        <div class="dropdown">
                            <button class="btn btn-light border-0 dropdown-toggle d-flex align-items-center gap-2 p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="rounded-circle bg-primary text-white fw-semibold d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="d-none d-sm-inline fw-semibold small text-secondary">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li class="dropdown-header small text-muted">Masuk sebagai {{ Auth::user()->email }}</li>
                                <li><a class="dropdown-item small" href="{{ route('profile.show') }}"><i class="bi bi-person me-2"></i>Profil Akun</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item small text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content Container -->
            <main class="p-3 p-md-4 flex-grow-1">
                <!-- Flash Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                        <div>{{ session('info') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-exclamation-circle-fill fs-5 text-warning"></i>
                            <strong>Terdapat kesalahan pada input:</strong>
                        </div>
                        <ul class="mb-0 ps-4 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Yield Content -->
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-top py-3 px-4 text-center text-muted small">
                <div class="container-fluid">
                    <p class="mb-0">
                        &copy; {{ date('Y') }} <strong>Perpustakaan Digital Sekolah</strong>. Dibangun menggunakan Laravel & Bootstrap 5.
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Reusable Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" id="deleteModalLabel">
                        <i class="bi bi-exclamation-octagon-fill"></i> Konfirmasi Penghapusan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-secondary py-3">
                    <p class="mb-1" id="deleteModalMessage">Apakah Anda yakin ingin menghapus data ini?</p>
                    <small class="text-danger">Tindakan ini permanen dan tidak dapat dibatalkan.</small>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Custom App Script -->
    <script>
        // Sidebar Toggle for Mobile Devices
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                sidebarBackdrop.classList.toggle('show');
            });
        }

        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarBackdrop.classList.remove('show');
            });
        }

        // Universal Delete Modal Trigger
        const deleteConfirmModal = document.getElementById('deleteConfirmModal');
        if (deleteConfirmModal) {
            deleteConfirmModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const actionUrl = button.getAttribute('data-action');
                const itemName = button.getAttribute('data-name') || 'data ini';

                const deleteForm = document.getElementById('deleteForm');
                deleteForm.setAttribute('action', actionUrl);

                const deleteModalMessage = document.getElementById('deleteModalMessage');
                deleteModalMessage.innerHTML = `Apakah Anda yakin ingin menghapus <strong>"${itemName}"</strong>?`;
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
