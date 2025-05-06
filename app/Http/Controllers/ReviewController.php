<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // ✅ List all reviews for a specific book
    public function index($bookId)
    {
        $book = Book::findOrFail($bookId);

        $reviews = $book->reviews()->with('user')->latest()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List of reviews for book',
            'data' => [
                'book' => $book->title,
                'reviews' => $reviews
            ]
        ]);
    }

    // ✅ Store a new review (hanya 1 review per user per book)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // ❗Cek jika user sudah review buku yang sama
        $existingReview = Review::where('book_id', $validated['book_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already reviewed this book.'
            ], 422);
        }

        $review = Review::create([
            'user_id' => $request->user()->id,
            'book_id' => $validated['book_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Review submitted successfully',
            'data' => $review
        ], 201);
    }

    // ✅ Update an existing review
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

    // ✅ Delete a review
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
