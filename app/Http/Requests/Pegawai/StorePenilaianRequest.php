<?php

namespace App\Http\Requests\Pegawai;

use Illuminate\Foundation\Http\FormRequest;

class StorePenilaianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'pegawai';
    }

    public function rules(): array
    {
        return [
            'skor' => ['required', 'array', 'min:1'],
            'skor.*' => ['required', 'integer', 'between:1,5'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'skor.required' => 'Seluruh pertanyaan indikator harus diisi nilai.',
            'skor.*.between' => 'Nilai skor harus berkisar antara 1 sampai 5.',
        ];
    }
}
