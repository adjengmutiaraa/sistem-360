<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nama_unit' => ['required', 'string', 'max:255'],
            'kode_unit' => ['nullable', 'string', 'max:50', 'unique:units,kode_unit'],
        ];
    }
}
