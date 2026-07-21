<?php

namespace App\Http\Requests\Pegawai;

use App\Models\PenugasanPenilaian;
use App\Models\PeriodePenilaian;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class PilihRekanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Pegawai');
    }

    public function rules(): array
    {
        return [
            'rekan_ids' => ['required', 'array', 'size:3'],
            'rekan_ids.*' => ['required', 'integer', 'exists:users,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            $rekanIds = $this->input('rekan_ids', []);

            if (in_array($user->id, $rekanIds)) {
                $validator->errors()->add('rekan_ids', 'Anda tidak dapat memilih diri Anda sendiri sebagai rekan kerja.');
            }

            $periode = PeriodePenilaian::getPeriodeAktif();
            if (! $periode) {
                $validator->errors()->add('rekan_ids', 'Tidak ada periode penilaian yang sedang aktif.');

                return;
            }

            foreach ($rekanIds as $dinilaiId) {
                $targetUser = User::find($dinilaiId);
                if (! $targetUser || $targetUser->jabatan?->level !== 'staff') {
                    $validator->errors()->add('rekan_ids', 'Rekan yang dipilih harus berstatus Staff.');
                }

                // Check maximum 3 peer evaluators for this evaluatee in this active period
                $currentPeerEvaluatorCount = PenugasanPenilaian::where('periode_penilaian_id', $periode->id)
                    ->where('dinilai_id', $dinilaiId)
                    ->where('jenis_penilai', 'rekan')
                    ->where('penilai_id', '!=', $user->id)
                    ->count();

                if ($currentPeerEvaluatorCount >= 3) {
                    $validator->errors()->add('rekan_ids', "Pegawai {$targetUser->name} sudah memiliki 3 evaluator rekan. Silakan pilih rekan staff lainnya.");
                }
            }
        });
    }
}

