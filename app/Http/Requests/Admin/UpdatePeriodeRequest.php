<?php

namespace App\Http\Requests\Admin;

use App\Models\PeriodePenilaian;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePeriodeRequest extends FormRequest
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
            $periodeId = $this->route('periode')?->id ?? $this->route('periode');

            if ($this->input('status') === 'aktif') {
                $hasActiveOther = PeriodePenilaian::where('status', 'aktif')
                    ->where('id', '!=', $periodeId)
                    ->exists();

                if ($hasActiveOther) {
                    $validator->errors()->add('status', 'Gagal mengaktifkan periode! Masih terdapat periode aktif lainnya.');
                }
            }
        });
    }
}
