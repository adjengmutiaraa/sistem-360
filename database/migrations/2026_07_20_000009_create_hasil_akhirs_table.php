<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_akhirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_penilaian_id')->constrained('periode_penilaians')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('nilai_atasan', 5, 2)->nullable();
            $table->decimal('nilai_rekan', 5, 2)->nullable();
            $table->decimal('nilai_bawahan', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2);
            $table->string('kategori')->nullable();
            $table->timestamps();

            $table->unique(['periode_penilaian_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_akhirs');
    }
};
