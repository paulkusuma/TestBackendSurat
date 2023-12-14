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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->date('Tanggal_Surat');
            // $table->string('Pengirim');
            $table->string('Pengirim');
            $table->string('Penerima');
            $table->string('Subjek');
            $table->text('Isi_Surat');
            $table->enum('status', ['Menunggu Tanda Tangan', 'Berhasil', 'Ditolak'])->default('Menunggu Tanda Tangan');
            $table->unsignedBigInteger('ditandatangani_oleh_koresponden_id')->nullable();
            $table->foreign('ditandatangani_oleh_koresponden_id')->references('id')->on('koresponden');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
