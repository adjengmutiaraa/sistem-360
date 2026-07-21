@extends('layouts.app')

@section('title', 'Hasil Penilaian Saya')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Hasil Penilaian Kinerja 360° Saya</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('pegawai.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Hasil Penilaian Saya</li>
            </ol>
        </nav>
    </div>
    @if($hasil && ! $isKetuaUmum)
        <div>
            <a href="{{ route('admin.hasil.pdf', [$selectedPeriode, $user]) }}" target="_blank" class="btn btn-outline-danger rounded-3">
                <i class="bi bi-file-earmark-pdf me-1"></i> Cetak / Download Raport PDF
            </a>
        </div>
    @endif
</div>

<!-- Periode Selector -->
<div class="card card-custom p-3 mb-4">
    <form method="GET" action="{{ route('pegawai.hasil-saya') }}" class="row align-items-center">
        <div class="col-12 col-md-4">
            <label class="form-label small fw-semibold text-secondary mb-1">PILIH PERIODE PENILAIAN</label>
            <select name="periode_id" class="form-select" onchange="this.form.submit()">
                @foreach($periodes as $p)
                    <option value="{{ $p->id }}" {{ $selectedPeriode?->id == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_periode }} ({{ strtoupper($p->status) }})
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

@if($isKetuaUmum)
    <div class="card card-custom p-5 text-center">
        <i class="bi bi-award-fill text-warning display-3 mb-3"></i>
        <h5 class="fw-bold">Informasi Ketua Umum</h5>
        <p class="text-secondary mb-0">Sebagai <strong>Ketua Umum</strong>, Anda bertugas memberikan penilaian kepada seluruh Kepala Bidang dan tidak dinilai oleh siapapun.</p>
    </div>
@elseif(! $hasil)
    <div class="card card-custom p-5 text-center">
        <i class="bi bi-hourglass-split text-muted display-4 mb-3"></i>
        <h5 class="fw-bold">Hasil Penilaian Belum Tersedia</h5>
        <p class="text-secondary mb-0">Hasil penilaian 360° untuk periode ini belum dikalkulasi oleh Administrator.</p>
    </div>
@else

    <!-- Response Count & Confidentiality Notice Card -->
    <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="bi bi-shield-lock-fill text-info fs-3 me-3"></i>
            <div>
                <h6 class="fw-bold mb-0">Kerahasiaan Penilai Dijamin (Anonim)</h6>
                <small class="text-secondary">Anda dapat melihat jumlah penilai dan komponen nilainya, namun identitas individu penilai dirahasiakan oleh sistem demi objektivitas.</small>
            </div>
        </div>
        <span class="badge bg-info text-dark px-3 py-2 fs-6 rounded-pill">Total {{ $totalPenilai }} Penilai</span>
    </div>

    <!-- Final Score Display Card -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card card-custom p-4 text-center bg-primary text-white h-100">
                <small class="text-white-50 text-uppercase fw-semibold">Nilai Akhir 360° Saya</small>
                <h1 class="display-3 fw-bold my-2">{{ number_format($hasil->nilai_akhir, 2) }}</h1>
                <div>
                    <span class="badge bg-white text-primary fs-6 px-4 py-2 rounded-pill shadow-sm fw-bold">
                        PREDIKAT: {{ strtoupper($hasil->kategori) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card card-custom p-4 h-100">
                <h5 class="fw-bold border-bottom pb-3 mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i> Breakdown Komponen Nilai & Responden</h5>
                <div class="row text-center g-3">
                    <div class="col-4">
                        <small class="text-secondary d-block">Nilai Atasan (50%)</small>
                        <h3 class="fw-bold text-dark mt-2 mb-0">{{ $hasil->nilai_atasan ?? '-' }}</h3>
                        <span class="badge bg-light text-secondary border mt-1">{{ $countAtasan }} Penilai Atasan</span>
                    </div>
                    <div class="col-4">
                        <small class="text-secondary d-block">Nilai Rekan ({{ $user->jabatan?->level === 'staff' ? '50%' : '30%' }})</small>
                        <h3 class="fw-bold text-dark mt-2 mb-0">{{ $hasil->nilai_rekan ?? '-' }}</h3>
                        <span class="badge bg-light text-secondary border mt-1">{{ $countRekan }} Penilai Rekan</span>
                    </div>
                    @if($user->jabatan?->level !== 'staff')
                        <div class="col-4">
                            <small class="text-secondary d-block">Nilai Bawahan (20%)</small>
                            <h3 class="fw-bold text-dark mt-2 mb-0">{{ $hasil->nilai_bawahan ?? '-' }}</h3>
                            <span class="badge bg-light text-secondary border mt-1">{{ $countBawahan }} Penilai Bawahan</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Anonymous Notes Card -->
    <div class="card card-custom p-4 mb-4">
        <h5 class="fw-bold border-bottom pb-3 mb-3"><i class="bi bi-chat-left-dots-fill text-primary me-2"></i> Masukan & Catatan Evaluator (Anonim)</h5>
        @forelse($penilaians as $p)
            @if($p->catatan)
                <div class="p-3 bg-light rounded-3 mb-2">
                    <small class="fw-bold text-primary mb-1 d-block">Masukan Evaluator ({{ strtoupper($p->jenis_penilai) }}):</small>
                    <p class="fst-italic text-dark mb-0">"{{ $p->catatan }}"</p>
                </div>
            @endif
        @empty
            <p class="text-muted mb-0">Belum ada catatan masukan evaluator.</p>
        @endforelse
    </div>
@endif
@endsection
