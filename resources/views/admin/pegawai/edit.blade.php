@extends('layouts.app')

@section('title', 'Edit Data Pegawai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Data Pegawai</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pegawai.index') }}" class="text-decoration-none">Pegawai</a></li>
                <li class="breadcrumb-item active">Edit {{ $pegawai->name }}</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.pegawai.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="card card-custom p-4">
    <form action="{{ route('admin.pegawai.update', $pegawai) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <!-- NIP -->
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold text-secondary">NIP <span class="text-danger">*</span></label>
                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $pegawai->nip) }}" required>
                @error('nip')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nama Lengkap -->
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold text-secondary">NAMA LENGKAP & GELAR <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $pegawai->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold text-secondary">EMAIL <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $pegawai->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold text-secondary">PASSWORD (KOSONGKAN JIKA TIDAK DIUBAH)</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Isi hanya jika ingin mengubah password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Role -->
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold text-secondary">ROLE SISTEM <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="pegawai" {{ old('role', $pegawai->role) == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                    <option value="admin" {{ old('role', $pegawai->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Jabatan -->
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold text-secondary">JABATAN</label>
                <select name="jabatan_id" class="form-select @error('jabatan_id') is-invalid @enderror">
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}" {{ old('jabatan_id', $pegawai->jabatan_id) == $jabatan->id ? 'selected' : '' }}>
                            {{ $jabatan->nama_jabatan }} ({{ strtoupper($jabatan->level) }})
                        </option>
                    @endforeach
                </select>
                @error('jabatan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Unit Kerja -->
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold text-secondary">UNIT KERJA</label>
                <select name="unit_id" class="form-select @error('unit_id') is-invalid @enderror">
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id', $pegawai->unit_id) == $unit->id ? 'selected' : '' }}>
                            {{ $unit->nama_unit }}
                        </option>
                    @endforeach
                </select>
                @error('unit_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Atasan Langsung -->
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold text-secondary">ATASAN LANGSUNG</label>
                <select name="atasan_id" class="form-select @error('atasan_id') is-invalid @enderror">
                    <option value="">-- Tanpa Atasan (Ketua Umum / Top Level) --</option>
                    @foreach($atasans as $atasan)
                        <option value="{{ $atasan->id }}" {{ old('atasan_id', $pegawai->atasan_id) == $atasan->id ? 'selected' : '' }}>
                            {{ $atasan->name }} ({{ $atasan->jabatan?->nama_jabatan ?? 'Pegawai' }})
                        </option>
                    @endforeach
                </select>
                @error('atasan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- No Telepon -->
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold text-secondary">NO TELEPON</label>
                <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon', $pegawai->telepon) }}">
                @error('telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection
