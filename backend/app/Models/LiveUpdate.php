<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveUpdate extends Model
{
    use HasFactory;

    protected $table = 'live_updates';

    protected $fillable = [
        'headline',
        'content',
        'tag',
        'time_str',
        'is_active',
        'published_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime'
    ];
}
