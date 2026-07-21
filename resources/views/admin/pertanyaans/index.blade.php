@extends('layouts.app')

@section('title', 'Mengelola Pertanyaan Penilaian')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Mengelola Pertanyaan Penilaian</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Pertanyaan Penilaian</li>
            </ol>
        </nav>
    </div>
    <div>
        <button class="btn btn-primary rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createPertanyaanModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pertanyaan Baru
        </button>
    </div>
</div>

@forelse($kategoris as $kategori)
    <div class="card card-custom p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1 text-primary"><i class="bi bi-tag-fill me-2"></i>{{ $kategori->nama_kategori }}</h5>
                <p class="text-secondary small mb-0">{{ $kategori->deskripsi }}</p>
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                Bobot: {{ number_format($kategori->bobot, 0) }}%
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">Urutan</th>
                        <th>Pertanyaan / Indikator Penilaian</th>
                        <th style="width: 160px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori->pertanyaans as $pertanyaan)
                        <tr>
                            <td><span class="badge bg-secondary rounded-circle px-2 py-1">{{ $pertanyaan->urutan }}</span></td>
                            <td class="fw-semibold">{{ $pertanyaan->pertanyaan }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary me-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editPertanyaanModal{{ $pertanyaan->id }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deletePertanyaanModal{{ $pertanyaan->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editPertanyaanModal{{ $pertanyaan->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header border-bottom-0">
                                        <h5 class="modal-title fw-bold">Edit Pertanyaan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.pertanyaans.update', $pertanyaan) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-secondary">KATEGORI PENILAIAN</label>
                                                <select name="kategori_nilai_id" class="form-select" required>
                                                    @foreach($allKategori as $kat)
                                                        <option value="{{ $kat->id }}" {{ $pertanyaan->kategori_nilai_id == $kat->id ? 'selected' : '' }}>
                                                            {{ $kat->nama_kategori }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-secondary">TEKS PERTANYAAN / INDIKATOR</label>
                                                <textarea name="pertanyaan" class="form-control" rows="3" required>{{ old('pertanyaan', $pertanyaan->pertanyaan) }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-secondary">URUTAN TAMPIL</label>
                                                <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $pertanyaan->urutan) }}" min="1" required>
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
                        <div class="modal fade" id="deletePertanyaanModal{{ $pertanyaan->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header border-bottom-0">
                                        <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus pertanyaan ini?
                                    </div>
                                    <div class="modal-footer border-top-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('admin.pertanyaans.destroy', $pertanyaan) }}" method="POST">
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
                            <td colspan="3" class="text-center text-muted py-3">Belum ada pertanyaan pada kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="card card-custom p-4 text-center text-muted">
        Belum ada kategori nilai.
    </div>
@endforelse

<!-- Create Modal -->
<div class="modal fade" id="createPertanyaanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Tambah Pertanyaan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pertanyaans.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">KATEGORI PENILAIAN</label>
                        <select name="kategori_nilai_id" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @foreach($allKategori as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">TEKS PERTANYAAN / INDIKATOR</label>
                        <textarea name="pertanyaan" class="form-control" rows="3" placeholder="Masukkan teks pertanyaan indikator..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">URUTAN TAMPIL</label>
                        <input type="number" name="urutan" class="form-control" value="1" min="1" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Pertanyaan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
