<?php
// File: database/migrations/0001_01_01_000005_create_laporan_harian_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('absensi_id')->nullable()->constrained('absensi')->nullOnDelete();
            $table->date('tanggal');
            $table->text('rencana_tugas');
            $table->text('catatan')->nullable();
            $table->enum('status', ['terkirim', 'pending'])->default('terkirim');
            $table->timestamps();

            $table->unique(['siswa_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_harian');
    }
};
