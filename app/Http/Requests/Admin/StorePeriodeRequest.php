<?php

namespace App\Http\Requests\Admin;

use App\Models\PeriodePenilaian;
use Illuminate\Foundation\Http\FormRequest;

class StorePeriodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nama_periode' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'status' => ['required', 'string', 'in:aktif,selesai'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->input('status') === 'aktif') {
                $hasActive = PeriodePenilaian::where('status', 'aktif')->exists();

                if ($hasActive) {
                    $validator->errors()->add('status', 'Tidak dapat membuat periode aktif baru karena masih ada periode lain yang berstatus AKTIF.');
                }
            }
        });
    }
}
