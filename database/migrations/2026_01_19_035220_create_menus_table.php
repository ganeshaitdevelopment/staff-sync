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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            
            // Route jadi nullable. Kenapa? Karena Menu Induk (Parent) biasanya cuma buat trigger dropdown, nggak ada link-nya.
            $table->string('route')->nullable(); 
            
            $table->text('icon_svg')->nullable(); // Icon boleh kosong buat sub-menu
            $table->json('allowed_roles'); 
            $table->integer('order')->default(0);
            
            // BARU: Kolom untuk relasi ke dirinya sendiri (Parent)
            // Kalau NULL = Menu Utama. Kalau Terisi = Sub Menu.
            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
