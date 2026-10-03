<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakingNews extends Model
{
    use HasFactory;

    protected $table = 'breaking_news';

    protected $fillable = [
        'headline',
        'slug',
        'article_id',
        'is_active',
        'priority',
        'expires_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
        'expires_at' => 'datetime'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
