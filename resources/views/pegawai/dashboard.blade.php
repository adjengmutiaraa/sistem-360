@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard Pegawai</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active">Dashboard Saya</li>
            </ol>
        </nav>
    </div>
    <div>
        <span class="badge bg-info text-dark px-3 py-2 rounded-pill">
            <i class="bi bi-person-badge me-1"></i> {{ auth()->user()->position?->nama_jabatan ?? 'Pegawai ASN' }}
        </span>
    </div>
</div>

<!-- Warning Alert if Staff needs to pick 3 peers -->
@if($needsPilihRekan)
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between p-3 rounded-3 mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-3 text-warning me-3"></i>
            <div>
                <h6 class="fw-bold mb-0">Perhatian: Anda Belum Memilih 3 Rekan Kerja Staff!</h6>
                <small class="text-secondary">Sebagai Staff, Anda diwajibkan memilih 3 rekan staff untuk dinilai pada periode aktif saat ini.</small>
            </div>
        </div>
        <a href="{{ route('pegawai.penilaian.pilih-rekan') }}" class="btn btn-warning fw-semibold px-4 rounded-3 text-nowrap">
            <i class="bi bi-people-fill me-1"></i> Pilih 3 Rekan Sekarang
        </a>
    </div>
@endif

<!-- Profile Info & Active Period Card -->
<div class="row g-4 mb-4">
    <!-- Profile Card -->
    <div class="col-12 col-lg-4">
        <div class="card card-custom p-4">
            <div class="text-center mb-3">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-2 shadow" style="width: 72px; height: 72px; font-size: 1.75rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                <p class="text-secondary small mb-2">NIP: {{ $user->nip }}</p>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">
                    {{ $user->position?->nama_jabatan ?? 'Belum ada position' }}
                </span>
            </div>
            <div class="border-top pt-3">
                <div class="d-flex justify-content-between mb-2">
                    <small class="text-secondary">department Kerja:</small>
                    <small class="fw-semibold">{{ $user->department?->nama_unit ?? '-' }}</small>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small class="text-secondary">Atasan Langsung:</small>
                    <small class="fw-semibold text-primary">{{ $user->atasan?->name ?? 'Ketua Umum (Top Level)' }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Period & Progress Card -->
    <div class="col-12 col-lg-8">
        <div class="card card-custom p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                <h5 class="fw-bold mb-0">Status Penilaian 360° Saya</h5>
                @if($periodeAktif)
                    <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-play-circle-fill me-1"></i> DIBUKA</span>
                @else
                    <span class="badge bg-secondary px-3 py-2 rounded-pill"><i class="bi bi-lock-fill me-1"></i> DITUTUP</span>
                @endif
            </div>

            @if($periodeAktif)
                <div class="p-3 bg-light rounded-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-primary mb-1">{{ $periodeAktif->nama_periode }}</h5>
                            <p class="text-secondary small mb-0">
                                <i class="bi bi-clock me-1"></i> Berlangsung: {{ \Carbon\Carbon::parse($periodeAktif->tanggal_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($periodeAktif->tanggal_selesai)->format('d M Y') }}
                            </p>
                        </div>
                        <a href="{{ route('pegawai.penilaian.index') }}" class="btn btn-primary rounded-3">
                            <i class="bi bi-pencil-square me-1"></i> Buka Lembar Penilaian
                        </a>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-semibold text-secondary">Progress Pengisian Saya</span>
                        <span class="fw-bold text-primary">{{ $totalSelesai }} / {{ $totalWajib }} Dinilai ({{ $progress }}%)</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar {{ $progress >= 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-calendar-x text-muted display-4 mb-2"></i>
                    <p class="text-secondary mb-0">Tidak ada periode penilaian 360° yang sedang aktif saat ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Assignment Preview Table -->
@if($periodeAktif && $penugasan->count() > 0)
    <div class="card card-custom p-4">
        <h5 class="fw-bold mb-3 border-bottom pb-3"><i class="bi bi-list-check text-primary me-2"></i> Daftar Pegawai Yang Harus Anda Nilai</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Pegawai Target</th>
                        <th>position & department</th>
                        <th>Jenis Hubungan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penugasan as $tugas)
                        <tr>
                            <td class="fw-bold text-dark">{{ $tugas->dinilai->name }}</td>
                            <td>{{ $tugas->dinilai->position?->nama_jabatan }} ({{ $tugas->dinilai->department?->nama_unit ?? '-' }})</td>
                            <td>
                                @if($tugas->jenis_penilai === 'atasan')
                                    <span class="badge bg-primary px-3 py-1 rounded-pill">Atasan Langsung</span>
                                @elseif($tugas->jenis_penilai === 'rekan')
                                    <span class="badge bg-info text-dark px-3 py-1 rounded-pill">Rekan Kerja</span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Bawahan</span>
                                @endif
                            </td>
                            <td>
                                @if($tugas->status === 'selesai')
                                    <span class="badge bg-success px-3 py-1 rounded-pill"><i class="bi bi-check-circle me-1"></i> SELESAI</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-1 rounded-pill"><i class="bi bi-clock me-1"></i> BELUM</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($tugas->status === 'selesai')
                                    <button class="btn btn-sm btn-light text-success border" disabled><i class="bi bi-check2-all"></i> Sudah Diisi</button>
                                @else
                                    <a href="{{ route('pegawai.penilaian.create', $tugas->dinilai) }}" class="btn btn-sm btn-primary px-3">
                                        <i class="bi bi-pencil-fill me-1"></i> Isi Penilaian
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection

