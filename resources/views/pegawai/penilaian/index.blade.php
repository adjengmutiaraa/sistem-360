@extends('layouts.app')

@section('title', 'Isi Penilaian 360°')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Pengisian Penilaian 360°</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('pegawai.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Isi Penilaian 360°</li>
            </ol>
        </nav>
    </div>
    @if($needsPilihRekan)
        <div>
            <a href="{{ route('pegawai.penilaian.pilih-rekan') }}" class="btn btn-warning rounded-3 shadow-sm font-semibold">
                <i class="bi bi-people-fill me-1"></i> Pilih 3 Rekan Staff
            </a>
        </div>
    @endif
</div>

@if(! $periodeAktif)
    <div class="card card-custom p-5 text-center">
        <i class="bi bi-lock-fill text-muted display-3 mb-3"></i>
        <h5 class="fw-bold">Form Penilaian Dikunci</h5>
        <p class="text-secondary mb-0">Saat ini tidak ada periode penilaian 360° yang sedang dibuka/aktif.</p>
    </div>
@else

    <!-- Status Completion Card -->
    <div class="card card-custom p-4 mb-4 border-start border-4 {{ $isComplete ? 'border-success' : 'border-warning' }}">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h5 class="fw-bold mb-1">Status Kelengkapan Penilaian Saya</h5>
                <p class="text-secondary small mb-0">
                    Periode: <strong>{{ $periodeAktif->nama_periode }}</strong> | Tanggal Berakhir: {{ \Carbon\Carbon::parse($periodeAktif->tanggal_selesai)->format('d M Y') }}
                </p>
            </div>
            <div>
                @if($isComplete)
                    <span class="badge bg-success fs-6 px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> LENGKAP & SELESAI</span>
                @else
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill"><i class="bi bi-exclamation-triangle-fill me-1"></i> BELUM LENGKAP ({{ $totalSelesai }}/{{ $totalWajib }})</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Target Evaluatees Table -->
    <div class="card card-custom p-4">
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-card-checklist text-primary me-2"></i> Daftar Pegawai Yang Harus Dinilai</h5>
            @if($isStaff && $peerAssignmentsCount < 3)
                <a href="{{ route('pegawai.penilaian.pilih-rekan') }}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-plus-circle me-1"></i> Tambah/Pilih 3 Rekan Staff (Tersedia: {{ $peerAssignmentsCount }}/3)
                </a>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Pegawai Target</th>
                        <th>Jabatan & Unit Kerja</th>
                        <th>Jenis Hubungan</th>
                        <th>Status Pengisian</th>
                        <th style="width: 160px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penugasan as $index => $tugas)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $tugas->dinilai->name }}</div>
                                <small class="text-secondary">NIP: {{ $tugas->dinilai->nip }}</small>
                            </td>
                            <td>
                                <div>{{ $tugas->dinilai->jabatan?->nama_jabatan ?? '-' }}</div>
                                <small class="text-secondary">{{ $tugas->dinilai->unit?->nama_unit ?? '-' }}</small>
                            </td>
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
                                    <button class="btn btn-sm btn-light text-success border px-3" disabled><i class="bi bi-check2-all"></i> Sudah Diisi</button>
                                @else
                                    <a href="{{ route('pegawai.penilaian.create', $tugas->dinilai) }}" class="btn btn-sm btn-primary px-3 shadow-sm">
                                        <i class="bi bi-pencil-fill me-1"></i> Isi Form
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada daftar penugasan penilaian untuk Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
