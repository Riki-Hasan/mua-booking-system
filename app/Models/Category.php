<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'base_price', 'duration_minutes']; 
    public function portfolios() {
    return $this->hasMany(Portfolio::class);
    }
}