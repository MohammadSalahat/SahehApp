<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatasetFakeNews extends Model
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
        'language',
        'content_hash',
        'detected_at',
        'confidence_score',
        'origin_dataset_name',
        'added_by_ai',
    ];

    /**
     * Boot the model and set up event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->content_hash)) {
                $model->content_hash = hash('sha256', $model->content);
            }
        });
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'detected_at' => 'datetime',
        'confidence_score' => 'decimal:4',
        'added_by_ai' => 'boolean',
    ];

    /**
     * Scope a query to search for similar content using fulltext search.
     */
    public function scopeSearchFulltext(Builder $query, string $searchText): Builder
    {
        return $query->whereRaw(
            'MATCH(title, content) AGAINST(? IN NATURAL LANGUAGE MODE)',
            [$searchText]
        );
    }

    /**
     * Scope a query to only include AI-added records.
     */
    public function scopeAddedByAi(Builder $query): Builder
    {
        return $query->where('added_by_ai', true);
    }

    /**
     * Scope a query to only include dataset records.
     */
    public function scopeFromDatasets(Builder $query): Builder
    {
        return $query->where('added_by_ai', false);
    }

    /**
     * Scope a query to filter by origin dataset.
     */
    public function scopeFromDataset(Builder $query, string $datasetName): Builder
    {
        return $query->where('origin_dataset_name', $datasetName);
    }

    /**
     * Scope a query to filter by minimum confidence score.
     */
    public function scopeMinimumConfidence(Builder $query, float $minScore): Builder
    {
        return $query->where('confidence_score', '>=', $minScore);
    }
}
