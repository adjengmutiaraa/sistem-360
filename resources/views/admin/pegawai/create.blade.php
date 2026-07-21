@extends('layouts.app')

@section('title', 'Tambah Pegawai Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Tambah Pegawai Baru</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pegawai.index') }}" class="text-decoration-none">Pegawai</a></li>
                <li class="breadcrumb-item active">Tambah</li>
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
    <form action="{{ route('admin.pegawai.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <!-- NIP -->
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold text-secondary">NIP <span class="text-danger">*</span></label>
                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" placeholder="Contoh: 199205052018011001" required>
                @error('nip')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nama Lengkap -->
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold text-secondary">NAMA LENGKAP & GELAR <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Dr. Budi Santoso, M.Si." required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold text-secondary">EMAIL <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Contoh: pegawai@sistem360.go.id" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold text-secondary">PASSWORD <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Role -->
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold text-secondary">ROLE SISTEM <span class="text-danger">*</span></label>
                                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}" {{ old('role') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Position -->
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold text-secondary">Position</label>
                <select name="position_id" class="form-select @error('position_id') is-invalid @enderror">
                    <option value="">-- Pilih Position --</option>
                    @foreach($positions as $Position)
                        <option value="{{ $Position->id }}" {{ old('position_id') == $Position->id ? 'selected' : '' }}>
                            {{ $Position->name }} ({{ strtoupper($Position->level) }})
                        </option>
                    @endforeach
                </select>
                @error('position_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Department Kerja -->
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold text-secondary">Department KERJA</label>
                <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                    <option value="">-- Pilih Department Kerja --</option>
                    @foreach($departments as $Department)
                        <option value="{{ $Department->id }}" {{ old('department_id') == $Department->id ? 'selected' : '' }}>
                            {{ $Department->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Atasan Langsung -->
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold text-secondary">ATASAN LANGSUNG (atasan_id)</label>
                <select name="atasan_id" class="form-select @error('atasan_id') is-invalid @enderror">
                    <option value="">-- Tanpa Atasan (Ketua Umum / Top Level) --</option>
                    @foreach($atasans as $atasan)
                        <option value="{{ $atasan->id }}" {{ old('atasan_id') == $atasan->id ? 'selected' : '' }}>
                            {{ $atasan->name }} ({{ $atasan->Position?->name ?? 'Pegawai' }})
                        </option>
                    @endforeach
                </select>
                @error('atasan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- No Telepon -->
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold text-secondary">NO TELEPON (OPSIONAL)</label>
                <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}" placeholder="Contoh: 081234567890">
                @error('telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mt-4 text-end">
                <button type="reset" class="btn btn-light me-2">Reset</button>
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Pegawai</button>
            </div>
        </div>
    </form>
</div>
@endsection


