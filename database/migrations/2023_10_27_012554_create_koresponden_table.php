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
        Schema::create('koresponden', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_User');
            $table->foreign('ID_User')->references('id')->on('users');
            $table->string('Tanda_Tangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koresponden');
    }
};
