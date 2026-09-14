@extends('layouts.app')

@section('title', 'Edit Transaksi Peminjaman #' . $borrowing->id)

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('borrowings.index') }}" class="btn btn-light border shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold text-dark mb-0">Edit Transaksi Peminjaman</h3>
            <small class="text-muted">Transaksi ID: #{{ $borrowing->id }}</small>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <!-- Ringkasan Info Terkait (Read Only) -->
                    <div class="alert alert-light border d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                        <div>
                            <small class="text-muted d-block">Peminjam:</small>
                            <strong class="text-dark">{{ $borrowing->member->nama ?? 'Siswa Dihapus' }}</strong> ({{ $borrowing->member->kelas ?? '-' }})
                        </div>
                        <div>
                            <small class="text-muted d-block">Buku:</small>
                            <strong class="text-dark">{{ $borrowing->book->judul ?? 'Buku Dihapus' }}</strong>
                        </div>
                        <div>
                            <small class="text-muted d-block">Petugas Pencatat:</small>
                            <span class="text-dark">{{ $borrowing->user->name ?? '-' }}</span>
                        </div>
                    </div>

                    <form action="{{ route('borrowings.update', $borrowing->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <!-- Tanggal Pinjam -->
                            <div class="col-12 col-md-4">
                                <label for="tanggal_pinjam" class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                                <input type="date"
                                       name="tanggal_pinjam"
                                       id="tanggal_pinjam"
                                       class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                       value="{{ old('tanggal_pinjam', $borrowing->tanggal_pinjam->format('Y-m-d')) }}"
                                       required>
                                @error('tanggal_pinjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Batas Kembali -->
                            <div class="col-12 col-md-4">
                                <label for="tanggal_kembali" class="form-label fw-semibold">Batas Pengembalian <span class="text-danger">*</span></label>
                                <input type="date"
                                       name="tanggal_kembali"
                                       id="tanggal_kembali"
                                       class="form-control @error('tanggal_kembali') is-invalid @enderror"
                                       value="{{ old('tanggal_kembali', $borrowing->tanggal_kembali->format('Y-m-d')) }}"
                                       required>
                                @error('tanggal_kembali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status Peminjaman -->
                            <div class="col-12 col-md-4">
                                <label for="status" class="form-label fw-semibold">Status Transaksi <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="Dipinjam" {{ old('status', $borrowing->status) === 'Dipinjam' ? 'selected' : '' }}>
                                        Dipinjam
                                    </option>
                                    <option value="Dikembalikan" {{ old('status', $borrowing->status) === 'Dikembalikan' ? 'selected' : '' }}>
                                        Dikembalikan
                                    </option>
                                    <option value="Terlambat" {{ old('status', $borrowing->status) === 'Terlambat' ? 'selected' : '' }}>
                                        Terlambat
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label for="catatan" class="form-label fw-semibold">Catatan Petugas</label>
                            <textarea name="catatan"
                                      id="catatan"
                                      rows="3"
                                      class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan', $borrowing->catatan) }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button"
                                    class="btn btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteConfirmModal"
                                    data-action="{{ route('borrowings.destroy', $borrowing->id) }}"
                                    data-name="transaksi peminjaman #{{ $borrowing->id }}">
                                <i class="bi bi-trash"></i> Hapus Transaksi
                            </button>

                            <div class="d-flex gap-2">
                                <a href="{{ route('borrowings.index') }}" class="btn btn-light border">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Perbarui Transaksi
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
                        <strong>Dibuat Pada:</strong> {{ $borrowing->created_at->format('d M Y, H:i') }}
                    </p>
                    <p class="small text-muted mb-2">
                        <strong>Status Saat Ini:</strong>
                        <span class="badge {{ $borrowing->status === 'Dikembalikan' ? 'bg-success' : ($borrowing->status === 'Terlambat' ? 'bg-danger' : 'bg-warning text-dark') }}">
                            {{ $borrowing->status }}
                        </span>
                    </p>
                    @if($borrowing->tanggal_pengembalian_aktual)
                        <p class="small text-muted mb-3">
                            <strong>Tanggal Selesai:</strong> {{ $borrowing->tanggal_pengembalian_aktual->format('d M Y') }}
                        </p>
                    @endif
                    <div class="alert alert-info small mb-0">
                        <i class="bi bi-info-circle me-1"></i> Mengubah status menjadi <strong>Dikembalikan</strong> akan otomatis menambahkan 1 ke stok buku di sistem.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
