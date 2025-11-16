<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Exception;

class UltraFastCacheService
{
    /**
     * Cache configuration
     */
    private const VERIFICATION_TTL = 300; // 5 minutes
    private const SIMILARITY_TTL = 900;   // 15 minutes for similarity results
    private const EXACT_MATCH_TTL = 3600; // 1 hour for exact matches
    private const STATS_TTL = 60;         // 1 minute for stats
    
    /**
     * Cache key prefixes
     */
    private const PREFIX_VERIFICATION = 'verify:';
    private const PREFIX_SIMILARITY = 'sim:';
    private const PREFIX_EXACT = 'exact:';
    private const PREFIX_STATS = 'stats:';
    
    /**
     * Redis connection
     */
    private $redis;
    
    public function __construct()
    {
        try {
            $this->redis = Redis::connection('default');
        } catch (Exception $e) {
            Log::warning('Redis not available, falling back to file cache', ['error' => $e->getMessage()]);
            $this->redis = null;
        }
    }
    
    /**
     * Get cached verification result
     */
    public function getCachedVerification(string $content): ?array
    {
        $key = $this->generateVerificationKey($content);
        
        try {
            if ($this->redis) {
                $cached = $this->redis->get($key);
                if ($cached) {
                    $result = json_decode($cached, true);
                    $result['cache_hit'] = true;
                    $result['cache_source'] = 'redis';
                    
                    Log::info('Redis cache hit', ['key' => substr($key, 0, 20) . '...']);
                    return $result;
                }
            }
            
            // Fallback to Laravel cache
            $cached = Cache::get($key);
            if ($cached) {
                $cached['cache_hit'] = true;
                $cached['cache_source'] = 'laravel';
                
                Log::info('Laravel cache hit', ['key' => substr($key, 0, 20) . '...']);
                return $cached;
            }
            
        } catch (Exception $e) {
            Log::error('Cache retrieval failed', ['error' => $e->getMessage()]);
        }
        
        return null;
    }
    
    /**
     * Cache verification result with smart TTL
     */
    public function cacheVerification(string $content, array $result): void
    {
        $key = $this->generateVerificationKey($content);
        
        // Determine TTL based on result quality
        $ttl = $this->getSmartTTL($result);
        
        // Add cache metadata
        $result['cached_at'] = now()->toISOString();
        $result['cache_ttl'] = $ttl;
        
        try {
            if ($this->redis) {
                $this->redis->setex($key, $ttl, json_encode($result));
                Log::info('Cached in Redis', [
                    'key' => substr($key, 0, 20) . '...',
                    'ttl' => $ttl,
                    'type' => $result['processing_method'] ?? 'unknown'
                ]);
            } else {
                Cache::put($key, $result, $ttl);
                Log::info('Cached in Laravel', [
                    'key' => substr($key, 0, 20) . '...',
                    'ttl' => $ttl
                ]);
            }
            
        } catch (Exception $e) {
            Log::error('Cache storage failed', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Cache exact match with longer TTL
     */
    public function cacheExactMatch(string $contentHash, array $result): void
    {
        $key = self::PREFIX_EXACT . $contentHash;
        
        try {
            if ($this->redis) {
                $this->redis->setex($key, self::EXACT_MATCH_TTL, json_encode($result));
            } else {
                Cache::put($key, $result, self::EXACT_MATCH_TTL);
            }
            
            Log::info('Cached exact match', ['hash' => substr($contentHash, 0, 12) . '...']);
            
        } catch (Exception $e) {
            Log::error('Exact match cache failed', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Get cached exact match
     */
    public function getCachedExactMatch(string $contentHash): ?array
    {
        $key = self::PREFIX_EXACT . $contentHash;
        
        try {
            if ($this->redis) {
                $cached = $this->redis->get($key);
                if ($cached) {
                    $result = json_decode($cached, true);
                    $result['cache_hit'] = true;
                    $result['cache_type'] = 'exact_match';
                    return $result;
                }
            }
            
            $cached = Cache::get($key);
            if ($cached) {
                $cached['cache_hit'] = true;
                $cached['cache_type'] = 'exact_match';
                return $cached;
            }
            
        } catch (Exception $e) {
            Log::error('Exact match cache retrieval failed', ['error' => $e->getMessage()]);
        }
        
        return null;
    }
    
    /**
     * Batch cache multiple results
     */
    public function batchCache(array $items): void
    {
        if (!$this->redis) {
            // Fallback to individual caching
            foreach ($items as $content => $result) {
                $this->cacheVerification($content, $result);
            }
            return;
        }
        
        try {
            $pipe = $this->redis->pipeline();
            
            foreach ($items as $content => $result) {
                $key = $this->generateVerificationKey($content);
                $ttl = $this->getSmartTTL($result);
                
                $result['cached_at'] = now()->toISOString();
                $result['cache_ttl'] = $ttl;
                
                $pipe->setex($key, $ttl, json_encode($result));
            }
            
            $pipe->execute();
            
            Log::info('Batch cached results', ['count' => count($items)]);
            
        } catch (Exception $e) {
            Log::error('Batch cache failed', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Cache performance statistics
     */
    public function cacheStats(array $stats): void
    {
        $key = self::PREFIX_STATS . 'performance';
        
        try {
            if ($this->redis) {
                $this->redis->setex($key, self::STATS_TTL, json_encode($stats));
            } else {
                Cache::put($key, $stats, self::STATS_TTL);
            }
            
        } catch (Exception $e) {
            Log::error('Stats cache failed', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Get cached performance statistics
     */
    public function getCachedStats(): ?array
    {
        $key = self::PREFIX_STATS . 'performance';
        
        try {
            if ($this->redis) {
                $cached = $this->redis->get($key);
                if ($cached) {
                    return json_decode($cached, true);
                }
            }
            
            return Cache::get($key);
            
        } catch (Exception $e) {
            Log::error('Stats cache retrieval failed', ['error' => $e->getMessage()]);
        }
        
        return null;
    }
    
    /**
     * Warm up cache with popular content
     */
    public function warmUpCache(array $popularContent): void
    {
        Log::info('Starting cache warm-up', ['items' => count($popularContent)]);
        
        foreach ($popularContent as $content) {
            // Only warm up if not already cached
            if (!$this->getCachedVerification($content)) {
                // This would trigger verification and caching
                // Implementation depends on your verification service
                Log::info('Would warm up cache for content', ['length' => strlen($content)]);
            }
        }
    }
    
    /**
     * Clear expired cache entries
     */
    public function clearExpiredCache(): int
    {
        $cleared = 0;
        
        try {
            if ($this->redis) {
                // Redis handles expiration automatically
                Log::info('Redis handles expiration automatically');
                return 0;
            }
            
            // For Laravel cache, we rely on the built-in garbage collection
            Log::info('Laravel cache handles expiration automatically');
            
        } catch (Exception $e) {
            Log::error('Cache cleanup failed', ['error' => $e->getMessage()]);
        }
        
        return $cleared;
    }
    
    /**
     * Get cache statistics
     */
    public function getCacheStatistics(): array
    {
        $stats = [
            'redis_available' => $this->redis !== null,
            'cache_backend' => $this->redis ? 'redis' : 'laravel',
            'ttl_config' => [
                'verification' => self::VERIFICATION_TTL,
                'similarity' => self::SIMILARITY_TTL,
                'exact_match' => self::EXACT_MATCH_TTL,
                'stats' => self::STATS_TTL,
            ],
        ];
        
        if ($this->redis) {
            try {
                $info = $this->redis->info('memory');
                $stats['redis_memory'] = [
                    'used_memory' => $info['used_memory'] ?? 'unknown',
                    'used_memory_human' => $info['used_memory_human'] ?? 'unknown',
                ];
                
                // Get key count with our prefixes
                $stats['cached_items'] = [
                    'verification' => $this->countKeys(self::PREFIX_VERIFICATION),
                    'exact_matches' => $this->countKeys(self::PREFIX_EXACT),
                    'stats' => $this->countKeys(self::PREFIX_STATS),
                ];
                
            } catch (Exception $e) {
                Log::error('Failed to get Redis stats', ['error' => $e->getMessage()]);
            }
        }
        
        return $stats;
    }
    
    /**
     * Generate cache key for verification
     */
    private function generateVerificationKey(string $content): string
    {
        return self::PREFIX_VERIFICATION . hash('sha256', trim($content));
    }
    
    /**
     * Determine smart TTL based on result quality
     */
    private function getSmartTTL(array $result): int
    {
        // Exact matches get longer TTL
        if (($result['processing_method'] ?? '') === 'exact_hash_match') {
            return self::EXACT_MATCH_TTL;
        }
        
        // High confidence results get longer TTL
        if (($result['highest_similarity'] ?? 0) > 0.8) {
            return self::SIMILARITY_TTL;
        }
        
        // Default TTL for other results
        return self::VERIFICATION_TTL;
    }
    
    /**
     * Count keys with specific prefix
     */
    private function countKeys(string $prefix): int
    {
        try {
            if ($this->redis) {
                $keys = $this->redis->keys($prefix . '*');
                return count($keys);
            }
        } catch (Exception $e) {
            Log::error('Key counting failed', ['error' => $e->getMessage()]);
        }
        
        return 0;
    }
    
    /**
     * Invalidate cache for specific content
     */
    public function invalidateCache(string $content): void
    {
        $key = $this->generateVerificationKey($content);
        
        try {
            if ($this->redis) {
                $this->redis->del($key);
            } else {
                Cache::forget($key);
            }
            
            Log::info('Cache invalidated', ['key' => substr($key, 0, 20) . '...']);
            
        } catch (Exception $e) {
            Log::error('Cache invalidation failed', ['error' => $e->getMessage()]);
        }
    }
}