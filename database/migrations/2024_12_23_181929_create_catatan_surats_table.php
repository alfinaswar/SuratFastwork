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
        Schema::create('catatan_surats', function (Blueprint $table) {
            $table->id();
            $table->string('idSurat')->nullable();
            $table->enum('Status', ['Verified', 'Revision'])->nullable();
            $table->text('Catatan')->nullable();
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
        Schema::dropIfExists('catatan_surats');
    }
};
