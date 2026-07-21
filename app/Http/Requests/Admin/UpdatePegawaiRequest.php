<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $userId = $this->route('pegawai')?->id ?? $this->route('pegawai');

        return [
            'nip' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'nip')->ignore($userId),
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'string', 'in:admin,pegawai'],
            'jabatan_id' => ['nullable', 'exists:jabatans,id'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'atasan_id' => ['nullable', 'exists:users,id'],
            'telepon' => ['nullable', 'string', 'max:20'],
        ];
    }
}
