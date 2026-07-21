@extends('layouts.app')

@section('title', 'Detail Raport Kinerja 360° - ' . $user->name)

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Detail Raport Kinerja 360° ASN</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.hasil.index') }}" class="text-decoration-none">Hasil Penilaian</a></li>
                <li class="breadcrumb-item active">{{ $user->name }}</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.hasil.pdf', [$periode, $user]) }}" target="_blank" class="btn btn-danger rounded-3 shadow-sm me-2">
            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak / Export PDF
        </a>
        <a href="{{ route('admin.hasil.index', ['periode_id' => $periode->id]) }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Header Info & Final Score Card -->
<div class="row g-4 mb-4">
    <div class="col-12 col-lg-4">
        <div class="card card-custom p-4 text-center">
            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-2 shadow mx-auto" style="width: 72px; height: 72px; font-size: 1.75rem;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
            <p class="text-secondary small mb-2">NIP: {{ $user->nip }}</p>
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">
                {{ $user->jabatan?->nama_jabatan }} - {{ $user->unit?->nama_unit }}
            </span>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold border-bottom pb-3 mb-3">Ringkasan Nilai Akhir & Predikat ({{ $periode->nama_periode }})</h5>
            <div class="row text-center g-3">
                <div class="col-3">
                    <small class="text-secondary d-block">NILAI ATASAN</small>
                    <h4 class="fw-bold mt-1 mb-0">{{ $hasil->nilai_atasan ?? '-' }}</h4>
                </div>
                <div class="col-3">
                    <small class="text-secondary d-block">NILAI REKAN</small>
                    <h4 class="fw-bold mt-1 mb-0">{{ $hasil->nilai_rekan ?? '-' }}</h4>
                </div>
                <div class="col-3">
                    <small class="text-secondary d-block">NILAI BAWAHAN</small>
                    <h4 class="fw-bold mt-1 mb-0">{{ $hasil->nilai_bawahan ?? '-' }}</h4>
                </div>
                <div class="col-3 border-start">
                    <small class="text-secondary d-block">NILAI AKHIR</small>
                    <h3 class="fw-bold text-primary mt-1 mb-0">{{ number_format($hasil->nilai_akhir ?? 0, 2) }}</h3>
                    <span class="badge bg-success px-3 py-1 rounded-pill mt-1">{{ $hasil->kategori ?? 'Belum Dikalkulasi' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Evaluation Notes Card -->
<div class="card card-custom p-4 mb-4">
    <h5 class="fw-bold border-bottom pb-3 mb-3"><i class="bi bi-chat-quote-fill text-primary me-2"></i> Masukan & Catatan Evaluator</h5>
    @forelse($penilaians as $p)
        @if($p->catatan)
            <div class="p-3 bg-light rounded-3 mb-2">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-semibold text-dark">{{ $p->penilai->name }} ({{ strtoupper($p->jenis_penilai) }})</span>
                    <small class="text-muted">{{ $p->created_at->format('d M Y') }}</small>
                </div>
                <p class="fst-italic text-secondary mb-0">"{{ $p->catatan }}"</p>
            </div>
        @endif
    @empty
        <p class="text-muted mb-0">Belum ada catatan masukan dari penilai.</p>
    @endforelse
</div>
@endsection
