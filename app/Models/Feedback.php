<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'article_title',
        'rating',
        'message',
        'verification_result',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that submitted the feedback.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include high ratings (4-5 stars).
     */
    public function scopeHighRatings($query)
    {
        return $query->whereIn('rating', [4, 5]);
    }

    /**
     * Scope a query to only include low ratings (1-2 stars).
     */
    public function scopeLowRatings($query)
    {
        return $query->whereIn('rating', [1, 2]);
    }

    /**
     * Scope a query to filter by rating.
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope a query to filter by verification result.
     */
    public function scopeByVerificationResult($query, $result)
    {
        return $query->where('verification_result', $result);
    }

    /**
     * Scope a query to search by article title using fulltext.
     */
    public function scopeSearchArticle($query, $searchTerm)
    {
        return $query->whereRaw(
            'MATCH(article_title) AGAINST(? IN BOOLEAN MODE)',
            [$searchTerm]
        );
    }

    /**
     * Scope a query to get recent feedbacks.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Check if the feedback is positive (4-5 stars).
     */
    public function isPositive(): bool
    {
        return $this->rating >= 4;
    }

    /**
     * Check if the feedback is negative (1-2 stars).
     */
    public function isNegative(): bool
    {
        return $this->rating <= 2;
    }

    /**
     * Get the average rating for a specific article.
     *
     * @param  string  $articleTitle
     */
    public static function averageRatingForArticle($articleTitle): float
    {
        return self::where('article_title', $articleTitle)->avg('rating') ?? 0;
    }

    /**
     * Get feedback statistics.
     */
    public static function getStatistics(): array
    {
        return [
            'total' => self::count(),
            'average_rating' => round(self::avg('rating'), 2),
            'positive' => self::whereIn('rating', [4, 5])->count(),
            'neutral' => self::where('rating', 3)->count(),
            'negative' => self::whereIn('rating', [1, 2])->count(),
        ];
    }
}
