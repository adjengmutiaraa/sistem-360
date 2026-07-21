@extends('layouts.app')

@section('title', 'Mengelola Position')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Mengelola Position</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Master Position</li>
            </ol>
        </nav>
    </div>
    <div>
        <button class="btn btn-primary rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createJabatanModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Position Baru
        </button>
    </div>
</div>

<!-- Table Card -->
<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Position</th>
                    <th>Level Hirarki</th>
                    <th>Jumlah Pegawai</th>
                    <th style="width: 160px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($positions as $index => $Position)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $Position->name }}</td>
                        <td>
                            @if($Position->level === 'ketua_umum')
                                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Ketua Umum</span>
                            @elseif($Position->level === 'kabid')
                                <span class="badge bg-success px-3 py-1 rounded-pill">Kepala Bidang</span>
                            @else
                                <span class="badge bg-info text-dark px-3 py-1 rounded-pill">Staff / Pelaksana</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">{{ $Position->users_count }} Orang</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editJabatanModal{{ $Position->id }}">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteJabatanModal{{ $Position->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editJabatanModal{{ $Position->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title fw-bold">Edit Position</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.positions.update', $Position) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-secondary">NAMA Position</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $Position->name) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-secondary">LEVEL HIRARKI</label>
                                            <select name="level" class="form-select" required>
                                                <option value="ketua_umum" {{ $Position->level === 'ketua_umum' ? 'selected' : '' }}>Ketua Umum</option>
                                                <option value="kabid" {{ $Position->level === 'kabid' ? 'selected' : '' }}>Kepala Bidang (Kabid)</option>
                                                <option value="staff" {{ $Position->level === 'staff' ? 'selected' : '' }}>Staff / Pelaksana</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteJabatanModal{{ $Position->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus Position <strong>{{ $Position->name }}</strong>?
                                </div>
                                <div class="modal-footer border-top-0">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.positions.destroy', $Position) }}" method="POST">
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
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data Position.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createJabatanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Tambah Position Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.positions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">NAMA Position</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Kepala Bidang Keuangan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">LEVEL HIRARKI</label>
                        <select name="level" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Level Hirarki --</option>
                            <option value="ketua_umum">Ketua Umum</option>
                            <option value="kabid">Kepala Bidang (Kabid)</option>
                            <option value="staff">Staff / Pelaksana</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Position</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

