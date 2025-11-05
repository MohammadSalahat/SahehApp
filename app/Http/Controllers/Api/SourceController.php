<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SourceController extends Controller
{
    /**
     * Get all sources with optional filters.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Source::query();

            // Filter active sources only
            if ($request->boolean('active_only', true)) {
                $query->active();
            }

            // Filter by minimum reliability
            if ($request->has('min_reliability')) {
                $query->minReliability($request->min_reliability);
            }

            // Order by reliability
            if ($request->boolean('order_by_reliability', false)) {
                $query->highReliability();
            } else {
                $query->orderBy('name');
            }

            $sources = $query->get();

            return response()->json([
                'success' => true,
                'count' => $sources->count(),
                'data' => $sources,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve sources',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single source by ID.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $source = Source::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $source,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Source not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Store a new source (for admin use).
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:sources,name',
            'url' => 'required|url|max:255',
            'description' => 'nullable|string|max:1000',
            'reliability_score' => 'nullable|numeric|min:0|max:1',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $source = Source::create([
                'name' => $request->name,
                'url' => $request->url,
                'description' => $request->description,
                'reliability_score' => $request->input('reliability_score', 0.5),
                'is_active' => $request->input('is_active', true),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Source created successfully',
                'data' => $source,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create source',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing source.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:sources,name,' . $id,
            'url' => 'sometimes|url|max:255',
            'description' => 'nullable|string|max:1000',
            'reliability_score' => 'nullable|numeric|min:0|max:1',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $source = Source::findOrFail($id);
            $source->update($request->only([
                'name',
                'url',
                'description',
                'reliability_score',
                'is_active',
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Source updated successfully',
                'data' => $source->fresh(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update source',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a source (soft delete).
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $source = Source::findOrFail($id);
            $source->delete();

            return response()->json([
                'success' => true,
                'message' => 'Source deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete source',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get trusted Saudi sources only.
     */
    public function trusted(): JsonResponse
    {
        try {
            $sources = Source::active()
                ->minReliability(0.7)
                ->highReliability()
                ->get();

            return response()->json([
                'success' => true,
                'count' => $sources->count(),
                'data' => $sources,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve trusted sources',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
