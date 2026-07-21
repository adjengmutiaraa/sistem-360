@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Profil Saya</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('pegawai.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Profil Saya</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row g-4">
    <!-- Main Profile Card -->
    <div class="col-12 col-md-5 col-lg-4">
        <div class="card card-custom p-4 text-center">
            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-3 shadow mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
            <p class="text-secondary small mb-2">NIP: {{ $user->nip ?? '-' }}</p>
            <div>
                <span class="badge bg-primary px-3 py-1 rounded-pill">
                    {{ $user->position?->nama_jabatan ?? 'Pegawai ASN' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Detailed Info Card -->
    <div class="col-12 col-md-7 col-lg-8">
        <div class="card card-custom p-4">
            <h5 class="fw-bold border-bottom pb-3 mb-3"><i class="bi bi-person-badge-fill text-primary me-2"></i> Informasi Pegawai</h5>
            <div class="row g-3">
                <div class="col-sm-6">
                    <small class="text-secondary d-block">Nama Lengkap & Gelar</small>
                    <span class="fw-bold">{{ $user->name }}</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-secondary d-block">NIP</small>
                    <span class="fw-bold">{{ $user->nip }}</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-secondary d-block">Email</small>
                    <span class="fw-bold">{{ $user->email }}</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-secondary d-block">No Telepon</small>
                    <span class="fw-bold">{{ $user->telepon ?? '-' }}</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-secondary d-block">position</small>
                    <span class="fw-bold text-primary">{{ $user->position?->nama_jabatan ?? '-' }}</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-secondary d-block">department Kerja</small>
                    <span class="fw-bold">{{ $user->department?->nama_unit ?? '-' }}</span>
                </div>
                <div class="col-sm-12 border-top pt-2">
                    <small class="text-secondary d-block">Atasan Langsung</small>
                    @if($user->atasan)
                        <span class="fw-bold text-primary"><i class="bi bi-person-up me-1"></i>{{ $user->atasan->name }} ({{ $user->atasan->position?->nama_jabatan }})</span>
                    @else
                        <span class="text-muted">Tidak Ada (Ketua Umum / Top Level)</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

