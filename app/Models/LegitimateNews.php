<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegitimateNews extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'legitimate_news';

    protected $fillable = [
        'title',
        'content',
        'source',
        'category',
        'url',
        'publish_date',
        'credibility_score',
        'language',
        'content_hash',
        'metadata',
        'verified',
    ];

    protected $casts = [
        'publish_date' => 'datetime',
        'credibility_score' => 'decimal:2',
        'metadata' => 'array',
        'verified' => 'boolean',
    ];

    protected $dates = [
        'publish_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Generate content hash for duplicate detection
     */
    public static function generateContentHash(string $title, string $content): string
    {
        return hash('sha256', trim($title).'|'.trim($content));
    }

    /**
     * Scope for filtering by source
     */
    public function scopeBySource($query, string $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Scope for filtering by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for recent news
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('publish_date', '>=', now()->subDays($days));
    }

    /**
     * Scope for high credibility news
     */
    public function scopeHighCredibility($query, float $threshold = 0.8)
    {
        return $query->where('credibility_score', '>=', $threshold);
    }
}
