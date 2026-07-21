@extends('layouts.app')

@section('title', 'Mengelola Department Kerja')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Mengelola Department Kerja</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Master Department Kerja</li>
            </ol>
        </nav>
    </div>
    <div>
        <button class="btn btn-primary rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createUnitModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Department Kerja Baru
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
                    <th>Nama Department Kerja</th>
                    <th>Kode Department</th>
                    <th>Jumlah Pegawai</th>
                    <th style="width: 160px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $index => $Department)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $Department->name }}</td>
                        <td>
                            <code class="bg-light text-primary border px-2 py-1 rounded">{{ $Department->kode_unit ?? '-' }}</code>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">{{ $Department->users_count }} Orang</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editUnitModal{{ $Department->id }}">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteUnitModal{{ $Department->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editUnitModal{{ $Department->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title fw-bold">Edit Department Kerja</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.departments.update', $Department) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-secondary">NAMA Department KERJA</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $Department->name) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-secondary">KODE Department (OPSIONAL)</label>
                                            <input type="text" name="kode_unit" class="form-control" value="{{ old('kode_unit', $Department->kode_unit) }}">
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
                    <div class="modal fade" id="deleteUnitModal{{ $Department->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus Department kerja <strong>{{ $Department->name }}</strong>?
                                </div>
                                <div class="modal-footer border-top-0">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.departments.destroy', $Department) }}" method="POST">
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
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data Department kerja.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createUnitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Tambah Department Kerja Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.departments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">NAMA Department KERJA</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Bidang Kepegawaian & SDM" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">KODE Department (OPSIONAL)</label>
                        <input type="text" name="kode_unit" class="form-control" placeholder="Contoh: KEPEGAWAIAN">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Department Kerja</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

