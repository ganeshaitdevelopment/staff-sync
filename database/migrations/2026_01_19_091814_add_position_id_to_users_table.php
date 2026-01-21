<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kita taruh setelah kolom email biar rapi
            // nullOnDelete artinya: Kalau jabatan "Staff IT" dihapus, usernya TETAP ADA (cuma jabatannya jadi kosong)
            $table->foreignId('position_id')->nullable()->after('email')->constrained('positions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
        });
    }
};
