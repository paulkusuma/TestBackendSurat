<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permohonans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nim');
            $table->string('semester');
            $table->string('isiSurat');
            $table->string('pilihanSurat');
            $table->enum('status', ['Menunggu Tanda Tangan', 'Berhasil', 'Ditolak'])->default('Menunggu Tanda Tangan');
            $table->string('pilihanProdi');
            $table->string('cuti')->default('1');
            $table->string('nomorTelepon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonans');
    }
};
