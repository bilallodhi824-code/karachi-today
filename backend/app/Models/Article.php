<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'articles';

    protected $fillable = [
        'uuid',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_id',
        'primary_category_id',
        'primary_author_id',
        'source_id',
        'source_item_id',
        'status',
        'publish_mode',
        'visibility',
        'content_type',
        'language',
        'reading_time_minutes',
        'views_count',
        'content_hash',
        'published_at',
        'scheduled_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'views_count' => 'integer',
        'reading_time_minutes' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'primary_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'primary_author_id');
    }

    public function source()
    {
        return $this->belongsTo(NewsSource::class, 'source_id');
    }

    public function versions()
    {
        return $this->hasMany(ArticleVersion::class, 'article_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }
}
