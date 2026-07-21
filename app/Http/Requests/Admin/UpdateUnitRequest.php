<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['Super Admin', 'Admin BKPSDM']);
    }

    public function rules(): array
    {
        $unitId = $this->route('unit')?->id ?? $this->route('unit');

        return [
            'nama_unit' => ['required', 'string', 'max:255'],
            'kode_unit' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('units', 'kode_unit')->ignore($unitId),
            ],
        ];
    }
}

