<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DatasetFakeNews;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DatasetFakeNewsController extends Controller
{
    /**
     * Store a new fake news entry from Python service.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'confidence_score' => 'nullable|numeric|min:0|max:1',
            'origin_dataset_name' => 'nullable|string|max:255',
            'added_by_ai' => 'nullable|boolean',
            'detected_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $fakeNews = DatasetFakeNews::create([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'confidence_score' => $request->input('confidence_score', 0),
                'origin_dataset_name' => $request->input('origin_dataset_name'),
                'added_by_ai' => $request->input('added_by_ai', false),
                'detected_at' => $request->input('detected_at', now()),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fake news entry created successfully',
                'data' => $fakeNews,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create fake news entry',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk store fake news entries (for dataset processing).
     */
    public function bulkStore(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'entries' => 'required|array',
            'entries.*.title' => 'required|string|max:255',
            'entries.*.content' => 'required|string',
            'entries.*.confidence_score' => 'nullable|numeric|min:0|max:1',
            'entries.*.origin_dataset_name' => 'nullable|string|max:255',
            'entries.*.added_by_ai' => 'nullable|boolean',
            'entries.*.detected_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $entries = collect($request->entries)->map(function ($entry) {
                return [
                    'title' => $entry['title'],
                    'content' => $entry['content'],
                    'confidence_score' => $entry['confidence_score'] ?? 0,
                    'origin_dataset_name' => $entry['origin_dataset_name'] ?? null,
                    'added_by_ai' => $entry['added_by_ai'] ?? false,
                    'detected_at' => $entry['detected_at'] ?? now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });

            DB::table('datasets_fake_news')->insert($entries->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Bulk entries created successfully',
                'count' => $entries->count(),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create bulk entries',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Search for similar fake news using fulltext search.
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:3',
            'threshold' => 'nullable|numeric|min:0|max:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $query = DatasetFakeNews::searchFulltext($request->input('query'));

            if ($request->has('threshold')) {
                $query->minimumConfidence($request->input('threshold'));
            }

            $limit = $request->input('limit', 10);
            $results = $query->limit($limit)->get();

            return response()->json([
                'success' => true,
                'count' => $results->count(),
                'data' => $results,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all fake news entries with optional filters.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = DatasetFakeNews::query();

            // Filter by dataset
            if ($request->has('dataset')) {
                $query->fromDataset($request->dataset);
            }

            // Filter by AI added
            if ($request->has('added_by_ai')) {
                $request->added_by_ai ? $query->addedByAi() : $query->fromDatasets();
            }

            // Filter by minimum confidence
            if ($request->has('min_confidence')) {
                $query->minimumConfidence($request->min_confidence);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $results = $query->orderBy('detected_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $results,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve entries',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single fake news entry by ID.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $fakeNews = DatasetFakeNews::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $fakeNews,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fake news entry not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update an existing fake news entry.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'confidence_score' => 'nullable|numeric|min:0|max:1',
            'origin_dataset_name' => 'nullable|string|max:255',
            'added_by_ai' => 'nullable|boolean',
            'detected_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $fakeNews = DatasetFakeNews::findOrFail($id);
            $fakeNews->update($request->only([
                'title',
                'content',
                'confidence_score',
                'origin_dataset_name',
                'added_by_ai',
                'detected_at',
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Fake news entry updated successfully',
                'data' => $fakeNews->fresh(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update fake news entry',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a fake news entry.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $fakeNews = DatasetFakeNews::findOrFail($id);
            $fakeNews->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fake news entry deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete fake news entry',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ===== Legitimate News Methods (For Balanced Dataset) =====

    /**
     * Store a new legitimate news entry from Python service.
     */
    public function storeLegitimate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'content' => 'required|string',
            'source' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'url' => 'nullable|string|max:1000',
            'publish_date' => 'nullable|date',
            'credibility_score' => 'nullable|numeric|min:0|max:1',
            'language' => 'nullable|string|max:10',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Generate content hash for duplicate detection
            $contentHash = \App\Models\LegitimateNews::generateContentHash(
                $request->input('title'),
                $request->input('content')
            );

            $legitimateNews = \App\Models\LegitimateNews::create([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'source' => $request->input('source'),
                'category' => $request->input('category', 'legal'),
                'url' => $request->input('url'),
                'publish_date' => $request->input('publish_date', now()),
                'credibility_score' => $request->input('credibility_score', 0.95),
                'language' => $request->input('language', 'ar'),
                'content_hash' => $contentHash,
                'metadata' => $request->input('metadata'),
                'verified' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Legitimate news entry created successfully',
                'data' => $legitimateNews,
            ], 201);

        } catch (\Exception $e) {
            // Check if it's a duplicate entry error
            if (str_contains($e->getMessage(), 'Duplicate entry') ||
                str_contains($e->getMessage(), 'content_hash')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate content detected',
                    'error' => 'This news content already exists in the database',
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to create legitimate news entry',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store multiple legitimate news entries in bulk.
     */
    public function bulkStoreLegitimate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'entries' => 'required|array|min:1|max:100',
            'entries.*.title' => 'required|string|max:500',
            'entries.*.content' => 'required|string',
            'entries.*.source' => 'required|string|max:100',
            'entries.*.category' => 'nullable|string|max:50',
            'entries.*.url' => 'nullable|string|max:1000',
            'entries.*.publish_date' => 'nullable|date',
            'entries.*.credibility_score' => 'nullable|numeric|min:0|max:1',
            'entries.*.language' => 'nullable|string|max:10',
            'entries.*.metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $entries = $request->input('entries');
        $created = 0;
        $duplicates = 0;
        $errors = 0;

        DB::beginTransaction();

        try {
            foreach ($entries as $entry) {
                try {
                    $contentHash = \App\Models\LegitimateNews::generateContentHash(
                        $entry['title'],
                        $entry['content']
                    );

                    \App\Models\LegitimateNews::create([
                        'title' => $entry['title'],
                        'content' => $entry['content'],
                        'source' => $entry['source'],
                        'category' => $entry['category'] ?? 'legal',
                        'url' => $entry['url'] ?? null,
                        'publish_date' => $entry['publish_date'] ?? now(),
                        'credibility_score' => $entry['credibility_score'] ?? 0.95,
                        'language' => $entry['language'] ?? 'ar',
                        'content_hash' => $contentHash,
                        'metadata' => $entry['metadata'] ?? null,
                        'verified' => true,
                    ]);

                    $created++;

                } catch (\Exception $e) {
                    if (str_contains($e->getMessage(), 'Duplicate entry') ||
                        str_contains($e->getMessage(), 'content_hash')) {
                        $duplicates++;
                    } else {
                        $errors++;
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bulk legitimate news creation completed',
                'data' => [
                    'total_entries' => count($entries),
                    'created' => $created,
                    'duplicates' => $duplicates,
                    'errors' => $errors,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create legitimate news entries',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get legitimate news entries with filtering.
     */
    public function indexLegitimate(Request $request): JsonResponse
    {
        try {
            $query = \App\Models\LegitimateNews::query();

            // Apply filters
            if ($request->has('source')) {
                $query->bySource($request->input('source'));
            }

            if ($request->has('category')) {
                $query->byCategory($request->input('category'));
            }

            if ($request->has('recent_days')) {
                $query->recent($request->input('recent_days'));
            }

            if ($request->has('min_credibility')) {
                $query->highCredibility($request->input('min_credibility'));
            }

            // Pagination
            $perPage = min($request->input('per_page', 20), 100);
            $legitimateNews = $query->orderBy('publish_date', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Legitimate news retrieved successfully',
                'data' => $legitimateNews,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve legitimate news',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
