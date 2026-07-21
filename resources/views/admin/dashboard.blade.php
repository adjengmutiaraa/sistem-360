@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard Administrator</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
    <div>
        <span class="badge bg-primary px-3 py-2 rounded-pill">
            <i class="bi bi-person-shield me-1"></i> Admin Panel
        </span>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-stat bg-primary p-3">
            <div class="fw-semibold text-white-50 text-uppercase small">Total Pegawai</div>
            <h2 class="fw-bold mb-0 text-white mt-1">{{ $totalPegawai ?? 0 }}</h2>
            <i class="bi bi-people-fill stat-icon text-white"></i>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-stat bg-info p-3">
            <div class="fw-semibold text-white-50 text-uppercase small">Total Staff</div>
            <h2 class="fw-bold mb-0 text-white mt-1">{{ $totalStaff ?? 0 }}</h2>
            <i class="bi bi-person-badge-fill stat-icon text-white"></i>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-stat bg-success p-3">
            <div class="fw-semibold text-white-50 text-uppercase small">Total Kepala Bidang</div>
            <h2 class="fw-bold mb-0 text-white mt-1">{{ $totalKabid ?? 0 }}</h2>
            <i class="bi bi-briefcase-fill stat-icon text-white"></i>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-stat bg-warning p-3">
            <div class="fw-semibold text-white-50 text-uppercase small">Ketua Umum</div>
            <h2 class="fw-bold mb-0 text-white mt-1">{{ $totalKetua ?? 0 }}</h2>
            <i class="bi bi-award-fill stat-icon text-white"></i>
        </div>
    </div>
</div>

<!-- Periode Penilaian Active Info -->
<div class="row g-4 mb-4">
    <div class="col-12 col-lg-8">
        <div class="card card-custom p-4">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                <h5 class="fw-bold mb-0">Status Periode Penilaian</h5>
                @if($periodeAktif)
                    <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i> AKTIF</span>
                @else
                    <span class="badge bg-secondary px-3 py-2 rounded-pill"><i class="bi bi-pause-circle me-1"></i> TIDAK ADA PERIODE AKTIF</span>
                @endif
            </div>

            @if($periodeAktif)
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="fw-bold text-primary mb-2">{{ $periodeAktif->nama_periode }}</h4>
                        <p class="text-secondary small mb-3">
                            <i class="bi bi-calendar3 me-1"></i> 
                            {{ \Carbon\Carbon::parse($periodeAktif->tanggal_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($periodeAktif->tanggal_selesai)->format('d M Y') }}
                        </p>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-calendar-x display-4 text-muted mb-2"></i>
                    <p class="text-secondary mb-0">Saat ini belum ada periode penilaian yang diaktifkan.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Akun Admin</h5>
            <div class="mb-2">
                <small class="text-secondary d-block">Nama Lengkap:</small>
                <span class="fw-bold">{{ auth()->user()->name }}</span>
            </div>
            <div class="mb-2">
                <small class="text-secondary d-block">Email Admin:</small>
                <span class="fw-bold">{{ auth()->user()->email }}</span>
            </div>
            <div class="mb-2">
                <small class="text-secondary d-block">NIP Admin:</small>
                <span class="fw-bold">{{ auth()->user()->nip ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Top 5 Ranking Section -->
@if(isset($top5Hasils) && $top5Hasils->count() > 0)
    <div class="card card-custom p-4">
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-trophy-fill text-warning me-2"></i> Top 5 Nilai Tertinggi ({{ $periodeAktif->nama_periode }})</h5>
            <a href="{{ route('admin.hasil.index', ['periode_id' => $periodeAktif->id]) }}" class="btn btn-sm btn-outline-primary">
                Lihat Selengkapnya <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;" class="text-center">Rank</th>
                        <th>Pegawai ASN</th>
                        <th>Jabatan & Unit</th>
                        <th class="text-center">Nilai Akhir 360°</th>
                        <th class="text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($top5Hasils as $rank => $h)
                        <tr>
                            <td class="text-center fw-bold">
                                @if($rank == 0)
                                    <span class="badge bg-warning text-dark rounded-circle px-2 py-1"><i class="bi bi-trophy-fill"></i> 1</span>
                                @elseif($rank == 1)
                                    <span class="badge bg-secondary rounded-circle px-2 py-1">2</span>
                                @elseif($rank == 2)
                                    <span class="badge bg-danger rounded-circle px-2 py-1">3</span>
                                @else
                                    <span class="badge bg-light text-dark border rounded-circle px-2 py-1">{{ $rank + 1 }}</span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">{{ $h->user->name }}</td>
                            <td>{{ $h->user->jabatan?->nama_jabatan }} ({{ $h->user->unit?->nama_unit ?? '-' }})</td>
                            <td class="text-center fw-bold text-primary fs-6">{{ number_format($h->nilai_akhir, 2) }}</td>
                            <td class="text-center">
                                <span class="badge bg-success px-3 py-1 rounded-pill">{{ $h->kategori }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
