<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsLicense extends Model
{
    use HasFactory;

    protected $table = 'news_licenses';

    protected $fillable = [
        'license_key',
        'client_name',
        'domain',
        'tier',
        'status',
        'rate_limit_per_minute',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'rate_limit_per_minute' => 'integer'
    ];
}
