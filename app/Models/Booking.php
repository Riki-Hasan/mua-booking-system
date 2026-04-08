<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    // Daftarkan semua kolom agar diizinkan untuk disimpan
    protected $fillable = [
        'order_id',
        'customer_name',
        'whatsapp_number',
        'address',
        'category_id',
        'location_id',
        'booking_date',
        'start_time',
        'end_time',
        'total_amount',
        'dp_amount',
        'payment_proof',
        'status',
    ];

    /**
     * Relasi ke Model Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Model Location
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}