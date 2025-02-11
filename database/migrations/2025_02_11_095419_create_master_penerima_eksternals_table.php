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
        Schema::create('master_penerima_eksternals', function (Blueprint $table) {
            $table->id();
            $table->string('Nama')->nullable();
            $table->string('Inisial')->nullable();
            $table->string('Jabatan')->nullable();
            $table->string('Departemen')->nullable();
            $table->string('Perusahaan')->nullable();
            $table->text('Alamat')->nullable();
            $table->string('Surel')->nullable();
            $table->string('Website')->nullable();
            $table->unsignedBigInteger('DibuatOleh')->nullable();
            $table->unsignedBigInteger('DieditOleh')->nullable();
            $table->unsignedBigInteger('DihapusOleh')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_penerima_eksternals');
    }
};
