<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $blueprint) {
            // Kita tambahkan kolom address setelah whatsapp_number
            $blueprint->text('address')->nullable()->after('whatsapp_number');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $blueprint) {
            $blueprint->dropColumn('address');
        });
    }
};