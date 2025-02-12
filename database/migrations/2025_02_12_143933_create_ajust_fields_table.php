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
        Schema::create('ajust_fields', function (Blueprint $table) {
            $table->id();
            $table->integer('JenisSurat')->nullable();
            $table->enum('PenerimaInternal', ['YA', 'TIDAK'])->nullable()->default('TIDAK');
            $table->enum('PenerimaEksternal', ['YA', 'TIDAK'])->nullable()->default('TIDAK');
            $table->enum('CCInternal', ['YA', 'TIDAK'])->nullable()->default('TIDAK');
            $table->enum('CCEksternal', ['YA', 'TIDAK'])->nullable()->default('TIDAK');
            $table->enum('BCCInternal', ['YA', 'TIDAK'])->nullable()->default('TIDAK');
            $table->enum('BCEksternal', ['YA', 'TIDAK'])->nullable()->default('TIDAK');
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
        Schema::dropIfExists('ajust_fields');
    }
};
