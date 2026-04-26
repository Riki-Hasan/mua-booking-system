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
        Schema::create('bundlings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Promo Bundling');
            $table->string('subject'); // Contoh: Makeup Engagement + Ortu
            $table->integer('price');
            $table->string('main_image'); // Foto utama (besar)
            $table->string('secondary_image'); // Foto kecil (lingkaran)
            $table->string('short_description'); // Yang muncul di card
            $table->text('description'); // Deskripsi lengkap di modal
            $table->string('include_text'); // Contoh: Include Free Softlens + Transport
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundlings');
    }
};
