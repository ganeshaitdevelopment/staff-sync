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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('month'); // Contoh: "01-2026"
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('overtime_salary', 15, 2);
            $table->decimal('total_salary', 15, 2);
            $table->enum('status', ['paid', 'pending'])->default('pending'); // Status pembayaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
