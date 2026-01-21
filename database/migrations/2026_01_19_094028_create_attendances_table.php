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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Siapa yang absen
            $table->date('date'); // Tanggal berapa
            $table->time('check_in_time'); // Jam masuk
            $table->time('check_out_time')->nullable(); // Jam pulang (awalnya kosong)
            $table->string('status')->default('present'); // Status (present, late, etc - opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
