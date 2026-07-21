<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nip' => ['required', 'string', 'max:50', 'unique:users,nip'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'string', 'in:admin,pegawai'],
            'jabatan_id' => ['nullable', 'exists:jabatans,id'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'atasan_id' => ['nullable', 'exists:users,id'],
            'telepon' => ['nullable', 'string', 'max:20'],
        ];
    }
}
