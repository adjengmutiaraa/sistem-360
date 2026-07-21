@extends('layouts.app')

@section('title', 'Form Penilaian 360° - ' . $dinilai->name)

@push('styles')
<style>
    .rating-container {
        display: flex;
        gap: 0.5rem;
    }
    .rating-option {
        flex: 1;
        text-align: center;
    }
    .rating-option input[type="radio"] {
        display: none;
    }
    .rating-label {
        display: block;
        padding: 0.6rem 0.4rem;
        background-color: #f1f5f9;
        border: 2px solid #cbd5e1;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        transition: all 0.2s ease;
    }
    .rating-option input[type="radio"]:checked + .rating-label {
        background-color: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .rating-label:hover {
        border-color: #3b82f6;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Form Kuisioner Penilaian 360°</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('pegawai.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pegawai.penilaian.index') }}" class="text-decoration-none">Penilaian 360°</a></li>
                <li class="breadcrumb-item active">{{ $dinilai->name }}</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('pegawai.penilaian.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
        </a>
    </div>
</div>

<!-- Target Evaluatee Header Card -->
<div class="card card-custom p-4 mb-4 border-start border-primary border-4">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 56px; height: 56px;">
            {{ strtoupper(substr($dinilai->name, 0, 1)) }}
        </div>
        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2">
                <h5 class="fw-bold mb-0">{{ $dinilai->name }}</h5>
                @if($penugasan->jenis_penilai === 'atasan')
                    <span class="badge bg-primary px-3 py-1 rounded-pill">Atasan Langsung</span>
                @elseif($penugasan->jenis_penilai === 'rekan')
                    <span class="badge bg-info text-dark px-3 py-1 rounded-pill">Rekan Kerja</span>
                @else
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Bawahan</span>
                @endif
            </div>
            <small class="text-secondary">NIP: {{ $dinilai->nip }} | {{ $dinilai->jabatan?->nama_jabatan }} - {{ $dinilai->unit?->nama_unit }}</small>
        </div>
    </div>
</div>

<!-- Evaluation Form -->
<form action="{{ route('pegawai.penilaian.store', $dinilai) }}" method="POST">
    @csrf
    @php $number = 1; @endphp

    @foreach($kategoris as $kategori)
        <div class="card card-custom p-4 mb-4">
            <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">
                <i class="bi bi-patch-check-fill me-2"></i>{{ $kategori->nama_kategori }}
            </h5>

            @foreach($kategori->pertanyaans as $pertanyaan)
                <div class="mb-4 pb-3 border-bottom">
                    <div class="fw-semibold text-dark mb-3">
                        <span class="badge bg-secondary me-2">{{ $number++ }}</span> {{ $pertanyaan->pertanyaan }}
                    </div>

                    <div class="rating-container">
                        <div class="rating-option">
                            <input type="radio" id="p{{ $pertanyaan->id }}_1" name="skor[{{ $pertanyaan->id }}]" value="1" {{ old("skor.{$pertanyaan->id}") == 1 ? 'checked' : '' }} required>
                            <label for="p{{ $pertanyaan->id }}_1" class="rating-label">1 - Sangat Kurang</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" id="p{{ $pertanyaan->id }}_2" name="skor[{{ $pertanyaan->id }}]" value="2" {{ old("skor.{$pertanyaan->id}") == 2 ? 'checked' : '' }}>
                            <label for="p{{ $pertanyaan->id }}_2" class="rating-label">2 - Kurang</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" id="p{{ $pertanyaan->id }}_3" name="skor[{{ $pertanyaan->id }}]" value="3" {{ old("skor.{$pertanyaan->id}") == 3 ? 'checked' : '' }}>
                            <label for="p{{ $pertanyaan->id }}_3" class="rating-label">3 - Cukup</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" id="p{{ $pertanyaan->id }}_4" name="skor[{{ $pertanyaan->id }}]" value="4" {{ old("skor.{$pertanyaan->id}", 4) == 4 ? 'checked' : '' }}>
                            <label for="p{{ $pertanyaan->id }}_4" class="rating-label">4 - Baik</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" id="p{{ $pertanyaan->id }}_5" name="skor[{{ $pertanyaan->id }}]" value="5" {{ old("skor.{$pertanyaan->id}") == 5 ? 'checked' : '' }}>
                            <label for="p{{ $pertanyaan->id }}_5" class="rating-label">5 - Sangat Baik</label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    <!-- Catatan / Masukan -->
    <div class="card card-custom p-4 mb-4">
        <h5 class="fw-bold mb-2"><i class="bi bi-chat-left-text me-2 text-primary"></i> Catatan & Masukan Konstruktif (Opsional)</h5>
        <textarea name="catatan" class="form-control" rows="3" placeholder="Tuliskan masukan atau saran pengembangan untuk pegawai ini...">{{ old('catatan') }}</textarea>
    </div>

    <!-- Submit Action -->
    <div class="card card-custom p-4 text-end">
        <button type="submit" class="btn btn-primary btn-lg px-5 font-semibold">
            <i class="bi bi-send-fill me-2"></i> Kirim Penilaian Ini
        </button>
    </div>
</form>
@endsection
