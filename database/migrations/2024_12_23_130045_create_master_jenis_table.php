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
        Schema::create('master_jenis', function (Blueprint $table) {
            $table->id();
            $table->string('idJenis', 50)->nullable();
            $table->string('JenisSurat', 255)->nullable();
            $table->enum('Aktif', ['Y', 'N'])->nullable()->default('Y');
            $table->string('DibuatOleh', 255)->nullable();
            $table->string('DieditOleh', 255)->nullable();
            $table->string('DihapusOleh', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_jenis');
    }
};
