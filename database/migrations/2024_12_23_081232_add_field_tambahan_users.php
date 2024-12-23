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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip', 25)->nullable()->after('id');
            $table->string('department', 25)->nullable()->after('email');
            $table->string('jabatan', 125)->nullable()->after('department');
            $table->string('hp', 15)->nullable()->after('jabatan');
            $table->string('position', 125)->nullable()->after('hp');
            $table->string('alamat', 255)->nullable()->after('position');
            $table->string('Foto', 255)->nullable()->after('alamat');
            $table->string('DigitalSign', 255)->nullable()->after('Foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
