<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Hapus constructor supaya middleware tidak dobel

    /**
     * List all reviews for a specific book with pagination
     */
    public function index($bookId)
    {
        $book = Book::findOrFail($bookId);

        $reviews = $book->reviews()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'List of reviews for book: ' . $book->title,
            'data' => [
                'book' => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'average_rating' => round($book->reviews()->avg('rating'), 2),
                    'reviews_count' => $book->reviews()->count(),
                ],
                'reviews' => $reviews
            ]
        ]);
    }

    /**
     * Store or update a review (one review per user per book)
     */
    public function store(Request $request, $bookId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $userId = $request->user()->id;

        $review = Review::updateOrCreate(
            ['user_id' => $userId, 'book_id' => $bookId],
            ['rating' => $validated['rating'], 'comment' => $validated['comment'] ?? null]
        );

        return response()->json([
            'status' => 'success',
            'message' => $review->wasRecentlyCreated ? 'Review submitted successfully' : 'Review updated successfully',
            'data' => $review
        ], $review->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Update an existing review explicitly
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Review updated successfully',
            'data' => $review
        ]);
    }

    /**
     * Delete a review
     */
    public function destroy(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Review deleted successfully'
        ]);
    }
}
