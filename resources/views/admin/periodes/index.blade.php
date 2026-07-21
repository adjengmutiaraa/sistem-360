@extends('layouts.app')

@section('title', 'Periode Penilaian 360°')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Periode Penilaian 360°</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Periode Penilaian</li>
            </ol>
        </nav>
    </div>
    <div>
        <button class="btn btn-primary rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createPeriodeModal">
            <i class="bi bi-calendar-plus me-1"></i> Buat Periode Penilaian Baru
        </button>
    </div>
</div>

<!-- Table Card -->
<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Periode</th>
                    <th>Rentang Tanggal</th>
                    <th>Status</th>
                    <th>Progress Penilaian</th>
                    <th style="width: 260px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periodes as $index => $periode)
                    @php
                        $progress = $periode->total_penugasan > 0 ? round(($periode->penugasan_selesai / $periode->total_penugasan) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <a href="{{ route('admin.periodes.show', $periode) }}" class="fw-bold text-primary text-decoration-none fs-6">
                                {{ $periode->nama_periode }}
                            </a>
                            @if($periode->deskripsi)
                                <small class="text-secondary d-block text-truncate" style="max-width: 250px;">{{ $periode->deskripsi }}</small>
                            @endif
                        </td>
                        <td>
                            <small class="fw-semibold">
                                <i class="bi bi-calendar-event me-1 text-muted"></i>
                                {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($periode->tanggal_selesai)->format('d M Y') }}
                            </small>
                        </td>
                        <td>
                            @if($periode->status === 'aktif')
                                <span class="badge bg-success px-3 py-1 rounded-pill"><i class="bi bi-play-circle-fill me-1"></i> AKTIF</span>
                            @else
                                <span class="badge bg-secondary px-3 py-1 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> SELESAI</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2" style="min-width: 160px;">
                                <div class="progress flex-grow-1" style="height: 8px;">
                                    <div class="progress-bar {{ $progress >= 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $progress }}%"></div>
                                </div>
                                <small class="fw-bold">{{ $progress }}%</small>
                            </div>
                            <small class="text-secondary" style="font-size: 0.75rem;">
                                {{ $periode->penugasan_selesai }} dari {{ $periode->total_penugasan }} Penugasan
                            </small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.periodes.show', $periode) }}" class="btn btn-outline-info" title="Detail Progress"><i class="bi bi-eye"></i> Detail</a>
                                
                                <form action="{{ route('admin.periodes.toggle-status', $periode) }}" method="POST" class="d-inline">
                                    @csrf
                                    @if($periode->status === 'aktif')
                                        <button type="submit" class="btn btn-outline-warning" title="Tutup Periode"><i class="bi bi-pause-fill"></i> Tutup</button>
                                    @else
                                        <button type="submit" class="btn btn-outline-success" title="Aktifkan Periode"><i class="bi bi-play-fill"></i> Aktifkan</button>
                                    @endif
                                </form>

                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPeriodeModal{{ $periode->id }}" title="Edit"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePeriodeModal{{ $periode->id }}" title="Hapus"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editPeriodeModal{{ $periode->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title fw-bold">Edit Periode Penilaian</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.periodes.update', $periode) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-secondary">NAMA PERIODE</label>
                                            <input type="text" name="nama_periode" class="form-control" value="{{ old('nama_periode', $periode->nama_periode) }}" required>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-secondary">TANGGAL MULAI</label>
                                                <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', \Carbon\Carbon::parse($periode->tanggal_mulai)->format('Y-m-d')) }}" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-secondary">TANGGAL SELESAI</label>
                                                <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', \Carbon\Carbon::parse($periode->tanggal_selesai)->format('Y-m-d')) }}" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-secondary">STATUS PERIODE</label>
                                            <select name="status" class="form-select" required>
                                                <option value="aktif" {{ $periode->status === 'aktif' ? 'selected' : '' }}>AKTIF</option>
                                                <option value="selesai" {{ $periode->status === 'selesai' ? 'selected' : '' }}>SELESAI (DITUTUP)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-secondary">DESKRIPSI (OPSIONAL)</label>
                                            <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $periode->deskripsi) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deletePeriodeModal{{ $periode->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus periode <strong>{{ $periode->nama_periode }}</strong> beserta seluruh data penugasannya?
                                </div>
                                <div class="modal-footer border-top-0">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.periodes.destroy', $periode) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada periode penilaian yang dibuat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createPeriodeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Buat Periode Penilaian Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.periodes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">NAMA PERIODE <span class="text-danger">*</span></label>
                        <input type="text" name="nama_periode" class="form-control" placeholder="Contoh: Juli 2026" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-secondary">TANGGAL MULAI <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', date('Y-m-01')) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-secondary">TANGGAL SELESAI <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', date('Y-m-t')) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">STATUS PERIODE <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>AKTIF (Otomatis Generate Matrix Penugasan 360°)</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>SELESAI (Draft/Ditutup)</option>
                        </select>
                        <small class="text-muted" style="font-size: 0.75rem;">* Catatan: Hanya boleh ada 1 periode aktif dalam satu waktu.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">DESKRIPSI (OPSIONAL)</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Catatan atau instruksi periode ini...">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Periode</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any() && !old('_method'))
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('createPeriodeModal'));
            modal.show();
        });
    </script>
    @endpush
@endif
@endsection
