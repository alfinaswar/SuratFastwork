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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('idJenis')->nullable();
            $table->string('NomorSurat')->nullable();
            $table->date('TanggalSurat')->nullable();
            $table->text('Lampiran')->nullable()->nullable();
            $table->string('PenerimaSurat')->nullable();
            $table->string('Perihal')->nullable();
            $table->longText('Isi')->nullable();
            $table->string('PengirimSurat')->nullable();
            $table->json('CarbonCopy')->nullable();
            $table->json('BlindCarbonCopy')->nullable();
            $table->enum('Status', ['Draft', 'Sumbited', 'Verified', 'Approved', 'Revision', 'Sent', 'Received', 'Read'])->default('Draft');
            $table->unsignedBigInteger('VerifiedBy')->nullable();
            $table->dateTime('VerifiedAt')->nullable();
            $table->unsignedBigInteger('ApprovedBy')->nullable();
            $table->dateTime('ApprovedAt')->nullable();
            $table->unsignedBigInteger('SentBy')->nullable();
            $table->dateTime('SentAt')->nullable();
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
        Schema::dropIfExists('surats');
    }
};
