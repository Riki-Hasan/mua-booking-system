<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['region_name', 'additional_price']; // Daftarkan kolom ini
}