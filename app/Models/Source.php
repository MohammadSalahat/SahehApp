<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Source extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'description',
        'reliability_score',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reliability_score' => 'decimal:2',
            'is_active' => 'boolean',
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
        'reliability_score' => 0.00,
        'is_active' => true,
    ];

    /**
     * Scope a query to only include active sources.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by reliability score (highest first).
     */
    public function scopeHighReliability($query)
    {
        return $query->orderBy('reliability_score', 'desc');
    }

    /**
     * Scope a query to filter sources by minimum reliability score.
     */
    public function scopeMinReliability($query, $minScore)
    {
        return $query->where('reliability_score', '>=', $minScore);
    }

    /**
     * Check if the source is considered reliable (score >= 0.7).
     */
    public function isReliable(): bool
    {
        return $this->reliability_score >= 0.70;
    }

    /**
     * Get the reliability rating as a percentage.
     */
    public function getReliabilityPercentageAttribute(): float
    {
        return $this->reliability_score * 100;
    }
}
