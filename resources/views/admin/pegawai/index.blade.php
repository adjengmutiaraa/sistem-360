@extends('layouts.app')

@section('title', 'Mengelola Pegawai')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Mengelola Data Pegawai</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Pegawai</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.pegawai.create') }}" class="btn btn-primary rounded-3 shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Pegawai Baru
        </a>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card card-custom p-3 mb-4">
    <form method="GET" action="{{ route('admin.pegawai.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 js-auto-search" placeholder="Ketik Nama, NIP, atau Email..." value="{{ request('search') }}" autocomplete="off">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <select name="unit_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Unit Kerja --</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3">
            <select name="jabatan_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Jabatan --</option>
                @foreach($jabatans as $jabatan)
                    <option value="{{ $jabatan->id }}" {{ request('jabatan_id') == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-2">
            @if(request()->hasAny(['search', 'unit_id', 'jabatan_id']))
                <a href="{{ route('admin.pegawai.index') }}" class="btn btn-outline-danger w-100">
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
                    <th>NIP & Nama Pegawai</th>
                    <th>Role</th>
                    <th>Jabatan</th>
                    <th>Unit Kerja</th>
                    <th>Atasan Langsung</th>
                    <th style="width: 150px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawais as $index => $pegawai)
                    <tr>
                        <td>{{ $pegawais->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold small" style="width: 36px; height: 36px;">
                                    {{ strtoupper(substr($pegawai->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $pegawai->name }}</div>
                                    <small class="text-secondary">NIP: {{ $pegawai->nip ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($pegawai->role === 'admin')
                                <span class="badge bg-danger px-2 py-1">ADMIN</span>
                            @else
                                <span class="badge bg-secondary px-2 py-1">PEGAWAI</span>
                            @endif
                        </td>
                        <td>
                            @if($pegawai->jabatan)
                                <span class="fw-medium text-dark">{{ $pegawai->jabatan->nama_jabatan }}</span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>{{ $pegawai->unit?->nama_unit ?? '-' }}</td>
                        <td>
                            @if($pegawai->atasan)
                                <small class="fw-semibold text-primary"><i class="bi bi-person-up me-1"></i>{{ $pegawai->atasan->name }}</small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.pegawai.show', $pegawai) }}" class="btn btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.pegawai.edit', $pegawai) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePegawaiModal{{ $pegawai->id }}" title="Hapus"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deletePegawaiModal{{ $pegawai->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus pegawai <strong>{{ $pegawai->name }}</strong> (NIP: {{ $pegawai->nip }})?
                                </div>
                                <div class="modal-footer border-top-0">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.pegawai.destroy', $pegawai) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Data pegawai tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $pegawais->links() }}
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
