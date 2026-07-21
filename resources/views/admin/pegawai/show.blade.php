@extends('layouts.app')

@section('title', 'Detail Pegawai - ' . $pegawai->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Detail Pegawai</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pegawai.index') }}" class="text-decoration-none">Pegawai</a></li>
                <li class="breadcrumb-item active">{{ $pegawai->name }}</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.pegawai.edit', $pegawai) }}" class="btn btn-primary rounded-3 me-2">
            <i class="bi bi-pencil-square me-1"></i> Edit Data
        </a>
        <a href="{{ route('admin.pegawai.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Main Profile Card -->
    <div class="col-12 col-md-5 col-lg-4">
        <div class="card card-custom p-4 text-center">
            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-3 shadow mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                {{ strtoupper(substr($pegawai->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $pegawai->name }}</h5>
            <p class="text-secondary small mb-2">NIP: {{ $pegawai->nip ?? '-' }}</p>
            <div>
                <span class="badge bg-primary px-3 py-1 rounded-pill">
                    {{ $pegawai->jabatan?->nama_jabatan ?? 'Belum ada jabatan' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Detailed Info Card -->
    <div class="col-12 col-md-7 col-lg-8">
        <div class="card card-custom p-4">
            <h5 class="fw-bold border-bottom pb-3 mb-3"><i class="bi bi-person-lines-fill text-primary me-2"></i> Informasi Detail</h5>
            <div class="row g-3">
                <div class="col-sm-6">
                    <small class="text-secondary d-block">Role Sistem</small>
                    <span class="fw-bold text-uppercase">{{ $pegawai->role }}</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-secondary d-block">Email</small>
                    <span class="fw-bold">{{ $pegawai->email }}</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-secondary d-block">Unit Kerja</small>
                    <span class="fw-bold">{{ $pegawai->unit?->nama_unit ?? '-' }}</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-secondary d-block">No Telepon</small>
                    <span class="fw-bold">{{ $pegawai->telepon ?? '-' }}</span>
                </div>
                <div class="col-sm-12">
                    <small class="text-secondary d-block">Atasan Langsung</small>
                    @if($pegawai->atasan)
                        <span class="fw-bold text-primary"><i class="bi bi-person-up me-1"></i>{{ $pegawai->atasan->name }} (NIP: {{ $pegawai->atasan->nip }})</span>
                    @else
                        <span class="text-muted">Tidak ada (Top Level / Ketua Umum)</span>
                    @endif
                </div>
            </div>

            <!-- List Bawahan -->
            <h5 class="fw-bold border-bottom pb-2 mt-4 mb-3"><i class="bi bi-people-fill text-success me-2"></i> Daftar Bawahan Langsung ({{ $pegawai->bawahan->count() }})</h5>
            @if($pegawai->bawahan->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($pegawai->bawahan as $bawahan)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="fw-semibold">{{ $bawahan->name }}</div>
                                <small class="text-secondary">NIP: {{ $bawahan->nip }} | {{ $bawahan->jabatan?->nama_jabatan }}</small>
                            </div>
                            <a href="{{ route('admin.pegawai.show', $bawahan) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted small mb-0">Pegawai ini tidak memiliki bawahan langsung.</p>
            @endif
        </div>
    </div>
</div>
@endsection
