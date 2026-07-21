<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePertanyaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['Super Admin', 'Admin BKPSDM']);
    }

    public function rules(): array
    {
        return [
            'pertanyaan' => ['required', 'string'],
            'kategori_nilai_id' => ['required', 'exists:kategori_nilais,id'],
            'urutan' => ['required', 'integer', 'min:1'],
        ];
    }
}

