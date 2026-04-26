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
        Schema::table('bookings', function (Blueprint $table) {
            // Biarkan category_id boleh kosong
        $table->unsignedBigInteger('category_id')->nullable()->change();
        
        // Tambahkan kolom bundling_id untuk mencatat promo yang dipesan
        $table->unsignedBigInteger('bundling_id')->nullable()->after('category_id');
        
        // Tambahkan relasi ke tabel bundlings (opsional tapi disarankan)
        $table->foreign('bundling_id')->references('id')->on('bundlings')->onDelete('set null');
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};
