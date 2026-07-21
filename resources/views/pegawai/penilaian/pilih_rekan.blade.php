@extends('layouts.app')

@section('title', 'Pilih 3 Rekan Kerja Staff')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Pilih 3 Rekan Kerja Staff</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('pegawai.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pegawai.penilaian.index') }}" class="text-decoration-none">Penilaian 360°</a></li>
                <li class="breadcrumb-item active">Pilih 3 Rekan</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('pegawai.penilaian.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="card card-custom p-4">
    <div class="alert alert-info border-0 bg-info bg-opacity-10 text-dark rounded-3 mb-4">
        <i class="bi bi-info-circle-fill me-2 text-info fs-5"></i>
        <strong>Petunjuk Pemilihan Rekan:</strong> Silakan pilih <strong>tepat 3 rekan kerja berlevel Staff</strong> yang akan Anda nilai. Sesuai ketentuan sistem, setiap pegawai hanya dapat dinilai oleh maksimal 3 rekan kerja. Pegawai yang sudah memiliki 3 evaluator rekan tidak dapat dipilih lagi.
    </div>

    <form action="{{ route('pegawai.penilaian.store-pilih-rekan') }}" method="POST">
        @csrf
        <div class="row g-3 mb-4">
            @for($i = 1; $i <= 3; $i++)
                <div class="col-12 col-md-4">
                    <div class="p-3 border rounded-3 bg-light">
                        <label class="form-label fw-bold text-primary mb-2">Pilihan Rekan Staff #{{ $i }} <span class="text-danger">*</span></label>
                        <select name="rekan_ids[]" class="form-select @error('rekan_ids') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih Rekan Staff --</option>
                            @foreach($staffs as $staff)
                                @php
                                    $isSelected = in_array($staff->id, old('rekan_ids', $existingPeerIds));
                                @endphp
                                <option value="{{ $staff->id }}" 
                                        {{ $isSelected ? 'selected' : '' }}
                                        {{ $staff->is_full && ! $isSelected ? 'disabled' : '' }}>
                                    {{ $staff->name }} ({{ $staff->department?->nama_unit ?? 'Staff' }})
                                    @if($staff->is_full && ! $isSelected)
                                        [PENUH - Max 3 Evaluator]
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endfor
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4 py-2 font-semibold">
                <i class="bi bi-check-circle me-1"></i> Simpan Pilihan 3 Rekan
            </button>
        </div>
    </form>
</div>
@endsection

