<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Store user feedback from Python service.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'article_title' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $feedback = Feedback::create([
                'user_id' => $request->user_id,
                'article_title' => $request->article_title,
                'rating' => $request->rating,
                'message' => $request->message,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully',
                'data' => $feedback,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit feedback',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all feedbacks with optional filters.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Feedback::with('user');

            // Filter by user
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Filter by rating
            if ($request->has('rating')) {
                $query->where('rating', $request->rating);
            }

            // Filter by minimum rating
            if ($request->has('min_rating')) {
                $query->where('rating', '>=', $request->min_rating);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $results = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $results,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve feedbacks',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get feedback statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total_feedbacks' => Feedback::count(),
                'average_rating' => round(Feedback::avg('rating'), 2),
                'rating_distribution' => Feedback::selectRaw('rating, COUNT(*) as count')
                    ->groupBy('rating')
                    ->orderBy('rating')
                    ->get()
                    ->pluck('count', 'rating'),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
