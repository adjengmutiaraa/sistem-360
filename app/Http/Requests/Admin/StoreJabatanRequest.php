<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreJabatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['Super Admin', 'Admin BKPSDM']);
    }

    public function rules(): array
    {
        return [
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'in:ketua_umum,kabid,staff'],
        ];
    }
}

