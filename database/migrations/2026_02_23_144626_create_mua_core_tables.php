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
        // 1. Tabel Wilayah
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('region_name');
            $table->integer('additional_price');
            $table->timestamps();
        });

        // 2. Tabel Paket Jasa
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('base_price');
            $table->integer('duration_minutes');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 3. Tabel Booking
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('customer_name');
            $table->string('whatsapp_number');
            $table->foreignId('category_id')->constrained();
            $table->foreignId('location_id')->nullable()->constrained();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('total_amount');
            $table->integer('dp_amount');
            $table->string('payment_proof')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mua_core_tables');
    }
};
