@extends('layouts.app')

@section('title', 'Laporan & Hasil Penilaian 360°')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Laporan & Rekapitulasi Hasil Penilaian 360°</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Hasil Penilaian</li>
            </ol>
        </nav>
    </div>
    @if($selectedPeriode)
        <div class="d-flex gap-2">
            <form action="{{ route('admin.hasil.kalkulasi', $selectedPeriode) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary rounded-3 shadow-sm">
                    <i class="bi bi-calculator me-1"></i> Hitung / Kalkulasi Nilai Akhir
                </button>
            </form>
            <a href="{{ route('admin.hasil.excel', $selectedPeriode) }}" class="btn btn-success rounded-3 shadow-sm">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
        </div>
    @endif
</div>

<!-- Periode Selector & Filter Card -->
<div class="card card-custom p-3 mb-4">
    <form method="GET" action="{{ route('admin.hasil.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-3">
            <label class="form-label small fw-semibold text-secondary mb-1">PILIH PERIODE</label>
            <select name="periode_id" class="form-select" onchange="this.form.submit()">
                @foreach($periodes as $p)
                    <option value="{{ $p->id }}" {{ $selectedPeriode?->id == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_periode }} ({{ strtoupper($p->status) }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3">
            <label class="form-label small fw-semibold text-secondary mb-1">CARI PEGAWAI</label>
            <input type="text" name="search" class="form-control js-auto-search" placeholder="Ketik Nama atau NIP..." value="{{ request('search') }}" autocomplete="off">
        </div>
        <div class="col-12 col-md-2">
            <label class="form-label small fw-semibold text-secondary mb-1">Department KERJA</label>
            <select name="department_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Department --</option>
                @foreach($departments as $Department)
                    <option value="{{ $Department->id }}" {{ request('department_id') == $Department->id ? 'selected' : '' }}>{{ $Department->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-2">
            <label class="form-label small fw-semibold text-secondary mb-1">Position</label>
            <select name="position_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Position --</option>
                @foreach($positions as $Position)
                    <option value="{{ $Position->id }}" {{ request('position_id') == $Position->id ? 'selected' : '' }}>{{ $Position->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-2 d-flex align-items-end mt-4">
            @if(request()->hasAny(['search', 'department_id', 'position_id']))
                <a href="{{ route('admin.hasil.index', ['periode_id' => $selectedPeriode?->id]) }}" class="btn btn-outline-danger w-100">
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
    @if(! $selectedPeriode)
        <div class="text-center py-4 text-muted">Belum ada periode penilaian.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">Rank</th>
                        <th>Pegawai ASN</th>
                        <th>Position & Department</th>
                        <th class="text-center">Nilai Atasan (50%)</th>
                        <th class="text-center">Nilai Rekan (30%/50%)</th>
                        <th class="text-center">Nilai Bawahan (20%)</th>
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-center">Kategori Predikat</th>
                        <th style="width: 130px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hasils as $index => $h)
                        <tr>
                            <td class="text-center">
                                @if($hasils->firstItem() + $index == 1)
                                    <span class="badge bg-warning text-dark fs-6 rounded-circle px-2 py-1"><i class="bi bi-trophy-fill"></i> 1</span>
                                @elseif($hasils->firstItem() + $index == 2)
                                    <span class="badge bg-secondary fs-6 rounded-circle px-2 py-1">2</span>
                                @elseif($hasils->firstItem() + $index == 3)
                                    <span class="badge bg-danger fs-6 rounded-circle px-2 py-1">3</span>
                                @else
                                    <span class="badge bg-light text-dark border rounded-circle px-2 py-1">{{ $hasils->firstItem() + $index }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $h->user->name }}</div>
                                <small class="text-secondary">NIP: {{ $h->user->nip }}</small>
                            </td>
                            <td>
                                <div>{{ $h->user->Position?->name ?? '-' }}</div>
                                <small class="text-secondary">{{ $h->user->Department?->name ?? '-' }}</small>
                            </td>
                            <td class="text-center font-monospace">{{ $h->nilai_atasan ?? '-' }}</td>
                            <td class="text-center font-monospace">{{ $h->nilai_rekan ?? '-' }}</td>
                            <td class="text-center font-monospace">{{ $h->nilai_bawahan ?? '-' }}</td>
                            <td class="text-center">
                                <span class="fw-bold text-primary fs-6">{{ number_format($h->nilai_akhir, 2) }}</span>
                            </td>
                            <td class="text-center">
                                @if($h->kategori === 'Sangat Baik')
                                    <span class="badge bg-success px-3 py-1 rounded-pill">Sangat Baik</span>
                                @elseif($h->kategori === 'Baik')
                                    <span class="badge bg-primary px-3 py-1 rounded-pill">Baik</span>
                                @elseif($h->kategori === 'Cukup')
                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Cukup</span>
                                @else
                                    <span class="badge bg-danger px-3 py-1 rounded-pill">Kurang</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.hasil.show', [$selectedPeriode, $h->user]) }}" class="btn btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.hasil.pdf', [$selectedPeriode, $h->user]) }}" target="_blank" class="btn btn-outline-danger" title="Download PDF"><i class="bi bi-file-earmark-pdf"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Belum ada data kalkulasi nilai akhir untuk periode ini. Silakan klik tombol <strong>"Hitung / Kalkulasi Nilai Akhir"</strong>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $hasils->links() }}
        </div>
    @endif
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

