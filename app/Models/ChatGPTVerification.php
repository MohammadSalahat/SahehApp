<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatGPTVerification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chatgpt_verifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'original_text',
        'language',
        'category',
        'model_used',
        'is_potentially_fake',
        'confidence_score',
        'credibility_level',
        'analysis',
        'warning_signs',
        'recommendation',
        'verification_tips',
        'related_topics',
        'fact_check_sources',
        'sources_checked',
        'source_verification_status',
        'trusted_sources_used',
        'tokens_used',
        'processing_time_ms',
        'user_ip',
        'user_id',
        'status',
        'error_message',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_potentially_fake' => 'boolean',
        'confidence_score' => 'decimal:4',
        'analysis' => 'array',
        'warning_signs' => 'array',
        'recommendation' => 'array',
        'verification_tips' => 'array',
        'related_topics' => 'array',
        'fact_check_sources' => 'array',
        'sources_checked' => 'array',
        'source_verification_status' => 'array',
        'trusted_sources_used' => 'array',
        'tokens_used' => 'integer',
        'processing_time_ms' => 'integer',
    ];

    /**
     * Get the user who requested the verification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for potentially fake news
     */
    public function scopePotentiallyFake($query)
    {
        return $query->where('is_potentially_fake', true);
    }

    /**
     * Scope for specific language
     */
    public function scopeLanguage($query, string $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Scope for specific category
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get credibility color class for UI
     */
    public function getCredibilityColorAttribute(): string
    {
        return match ($this->credibility_level) {
            'high' => 'text-green-600',
            'medium' => 'text-yellow-600',
            'low' => 'text-orange-600',
            'very_low' => 'text-red-600',
            default => 'text-gray-600',
        };
    }

    /**
     * Get credibility icon
     */
    public function getCredibilityIconAttribute(): string
    {
        return match ($this->credibility_level) {
            'high' => 'check-circle',
            'medium' => 'exclamation-circle',
            'low' => 'x-circle',
            'very_low' => 'x-circle',
            default => 'question-mark-circle',
        };
    }
}
