<?php
// File: database/migrations/0001_01_01_000004_create_absensi_table.php

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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam');
            $table->string('foto_wajah');
            $table->string('tanda_tangan')->nullable();
            $table->enum('status', ['hadir', 'izin', 'sakit'])->default('hadir');
            $table->timestamps();

            $table->unique(['siswa_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
