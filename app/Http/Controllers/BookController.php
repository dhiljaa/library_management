<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // 📚 Get all books with category detail and average rating
    public function index()
    {
        $books = Book::with('category')
                     ->withAvg('reviews', 'rating')
                     ->get();

        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    // 🔝 Get top 10 most borrowed books
    public function top()
    {
        $books = Book::with('category')
                     ->withCount('loans')
                     ->withAvg('reviews', 'rating')
                     ->orderByDesc('loans_count')
                     ->take(10)
                     ->get();

        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    // 📂 Get books by category ID
    public function byCategory($category_id)
    {
        $books = Book::with('category')
                     ->withAvg('reviews', 'rating')
                     ->where('category_id', $category_id)
                     ->get();

        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    // 🔍 Show detailed book info including reviews
    public function show($id)
    {
        $book = Book::with([
                    'category',
                    'reviews.user' // supaya bisa tampilkan nama user reviewer
                ])
                ->withAvg('reviews', 'rating')
                ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'publisher' => $book->publisher,
                'published_year' => $book->published_year,
                'description' => $book->description,
                'image_url' => $book->image_url,
                'quantity' => $book->quantity,
                'borrowed_count' => $book->borrowed_count,
                'category' => $book->category ? $book->category->name : null,
                'rating' => $book->rating,
                'reviews' => $book->reviews->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'user' => $review->user->name ?? 'Anonymous',
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'created_at' => $review->created_at->diffForHumans(),
                    ];
                }),
            ]
        ]);
    }
}
