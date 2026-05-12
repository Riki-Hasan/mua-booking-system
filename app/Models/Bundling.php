<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bundling extends Model
{
    protected $fillable = [
        'title', 
        'subject', 
        'price', 
        'main_image', 
        'secondary_image', 
        'short_description', 
        'description', 
        'include_text',
        'duration_minutes',
        'target_person_count', 
        'is_active',
    ];
}
