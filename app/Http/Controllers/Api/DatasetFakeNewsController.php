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
}
