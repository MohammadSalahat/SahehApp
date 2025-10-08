<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatasetsFakeNew extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'datasets_fake_news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'detected_at',
        'confidence_score',
        'origin_dataset_name',
        'added_by_ai',
        'content_hash',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'detected_at' => 'datetime',
            'confidence_score' => 'decimal:4',
            'added_by_ai' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $attributes = [
        'confidence_score' => 0.0000,
        'added_by_ai' => false,
    ];

    /**
     * Boot the model and add automatic content hash generation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->content_hash)) {
                $model->content_hash = hash('sha256', $model->title.$model->content);
            }
            if (empty($model->detected_at)) {
                $model->detected_at = now();
            }
        });
    }

    /**
     * Scope a query to only include AI-added records.
     */
    public function scopeAddedByAi($query)
    {
        return $query->where('added_by_ai', true);
    }

    /**
     * Scope a query to only include dataset records.
     */
    public function scopeFromDataset($query)
    {
        return $query->where('added_by_ai', false);
    }

    /**
     * Scope a query to filter by origin dataset.
     */
    public function scopeFromOrigin($query, $datasetName)
    {
        return $query->where('origin_dataset_name', $datasetName);
    }

    /**
     * Scope a query to filter by minimum confidence score.
     */
    public function scopeMinConfidence($query, $minScore)
    {
        return $query->where('confidence_score', '>=', $minScore);
    }

    /**
     * Scope a query to search using fulltext.
     */
    public function scopeFullTextSearch($query, $searchTerm)
    {
        return $query->whereRaw(
            'MATCH(title, content) AGAINST(? IN BOOLEAN MODE)',
            [$searchTerm]
        );
    }

    /**
     * Check if content is highly confident fake news (score >= 0.8).
     */
    public function isHighConfidence(): bool
    {
        return $this->confidence_score >= 0.8000;
    }

    /**
     * Get the confidence score as a percentage.
     */
    public function getConfidencePercentageAttribute(): float
    {
        return $this->confidence_score * 100;
    }

    /**
     * Search for similar content by text matching.
     *
     * @param  string  $title
     * @param  string  $content
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function searchSimilar($title, $content, $threshold = 0.5)
    {
        $searchTerm = $title.' '.$content;

        return self::select('*')
            ->selectRaw(
                'MATCH(title, content) AGAINST(? IN BOOLEAN MODE) as relevance_score',
                [$searchTerm]
            )
            ->whereRaw(
                'MATCH(title, content) AGAINST(? IN BOOLEAN MODE) > ?',
                [$searchTerm, $threshold]
            )
            ->orderBy('relevance_score', 'desc')
            ->get();
    }

    /**
     * Check if similar content exists by hash.
     *
     * @param  string  $title
     * @param  string  $content
     */
    public static function existsByHash($title, $content): bool
    {
        $hash = hash('sha256', $title.$content);

        return self::where('content_hash', $hash)->exists();
    }
}
