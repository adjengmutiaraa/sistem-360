@extends('layouts.app')

@section('title', 'Detail & Progress Periode - ' . $periode->nama_periode)

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Progress & Matriks Penugasan 360°</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.periodes.index') }}" class="text-decoration-none">Periode Penilaian</a></li>
                <li class="breadcrumb-item active">{{ $periode->nama_periode }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <form action="{{ route('admin.periodes.generate-penugasan', $periode) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-primary rounded-3 shadow-sm">
                <i class="bi bi-arrow-clockwise me-1"></i> Update/Generate Ulang Matrix
            </button>
        </form>
        <a href="{{ route('admin.periodes.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Progress Summary Card -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-3">
        <div class="card card-custom p-3 text-center border-start border-primary border-4">
            <small class="text-secondary fw-semibold">STATUS PERIODE</small>
            <h5 class="fw-bold mt-1 mb-0">
                @if($periode->status === 'aktif')
                    <span class="badge bg-success px-3 py-1 rounded-pill">AKTIF</span>
                @else
                    <span class="badge bg-secondary px-3 py-1 rounded-pill">SELESAI</span>
                @endif
            </h5>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card card-custom p-3 text-center border-start border-info border-4">
            <small class="text-secondary fw-semibold">TOTAL PENUGASAN</small>
            <h4 class="fw-bold mt-1 mb-0 text-dark">{{ $totalPenugasan }}</h4>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card card-custom p-3 text-center border-start border-success border-4">
            <small class="text-secondary fw-semibold">PENUGASAN SELESAI</small>
            <h4 class="fw-bold mt-1 mb-0 text-success">{{ $totalSelesai }}</h4>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card card-custom p-3 text-center border-start border-warning border-4">
            <small class="text-secondary fw-semibold">PROGRESS OVERALL</small>
            <h4 class="fw-bold mt-1 mb-0 text-primary">{{ $progress }}%</h4>
        </div>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card card-custom p-3 mb-4">
    <form method="GET" action="{{ route('admin.periodes.show', $periode) }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 js-auto-search" placeholder="Ketik Nama Penilai/Yang Dinilai..." value="{{ request('search') }}" autocomplete="off">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <select name="jenis_penilai" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Jenis Hubungan --</option>
                <option value="atasan" {{ request('jenis_penilai') == 'atasan' ? 'selected' : '' }}>Atasan</option>
                <option value="rekan" {{ request('jenis_penilai') == 'rekan' ? 'selected' : '' }}>Rekan Kerja</option>
                <option value="bawahan" {{ request('jenis_penilai') == 'bawahan' ? 'selected' : '' }}>Bawahan</option>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Mengisi</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Sudah Selesai</option>
            </select>
        </div>
        <div class="col-12 col-md-2">
            @if(request()->hasAny(['search', 'jenis_penilai', 'status']))
                <a href="{{ route('admin.periodes.show', $periode) }}" class="btn btn-outline-danger w-100">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            @else
                <button type="button" class="btn btn-light text-muted w-100" disabled>
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </button>
            @endif
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Penilai (Evaluator)</th>
                    <th>Yang Dinilai (Evaluatee)</th>
                    <th>Jenis Hubungan</th>
                    <th>Status Pengisian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penugasantable as $index => $tugas)
                    <tr>
                        <td>{{ $penugasantable->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-semibold">{{ $tugas->penilai->name }}</div>
                            <small class="text-secondary">{{ $tugas->penilai->jabatan?->nama_jabatan ?? 'Pegawai' }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold text-primary">{{ $tugas->dinilai->name }}</div>
                            <small class="text-secondary">{{ $tugas->dinilai->jabatan?->nama_jabatan ?? 'Pegawai' }}</small>
                        </td>
                        <td>
                            @if($tugas->jenis_penilai === 'atasan')
                                <span class="badge bg-primary px-3 py-1 rounded-pill">Atasan</span>
                            @elseif($tugas->jenis_penilai === 'rekan')
                                <span class="badge bg-info text-dark px-3 py-1 rounded-pill">Rekan Kerja</span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Bawahan</span>
                            @endif
                        </td>
                        <td>
                            @if($tugas->status === 'selesai')
                                <span class="badge bg-success px-3 py-1 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> SELESAI</span>
                            @else
                                <span class="badge bg-secondary px-3 py-1 rounded-pill"><i class="bi bi-clock-history me-1"></i> BELUM</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Data penugasan tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $penugasantable->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.js-auto-search').forEach(function(input) {
        if (input.value !== '') {
            input.focus();
            const len = input.value.length;
            input.setSelectionRange(len, len);
        }

        let timeout = null;
        input.addEventListener('keydown', function(e) {
            if (e.key === ' ') {
                clearTimeout(timeout);
            }
        });

        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                this.form.submit();
            }, 600);
        });
    });
</script>
@endpush
