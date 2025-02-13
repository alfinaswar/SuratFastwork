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
        Schema::table('surats', function (Blueprint $table) {
            $table->string('PenerimaSuratEks')->nullable()->after('PenerimaSurat');
            $table->longText('CarbonCopyEks')->nullable()->after('CarbonCopy');
            $table->longText('BlindCarbonCopyEks')->nullable()->after('BlindCarbonCopy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            //
        });
    }
};
