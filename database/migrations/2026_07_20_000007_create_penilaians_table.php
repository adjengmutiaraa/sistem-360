<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_penilaian_id')->constrained('periode_penilaians')->cascadeOnDelete();
            $table->foreignId('penilai_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('dinilai_id')->constrained('users')->cascadeOnDelete();
            $table->enum('jenis_penilai', ['atasan', 'rekan', 'bawahan']);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['periode_penilaian_id', 'penilai_id', 'dinilai_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
