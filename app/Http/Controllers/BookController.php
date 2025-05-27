<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // 📚 Get all books with category detail
    public function index()
    {
        $books = Book::with('category')->get();

        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    // 📈 Get top books based on number of loans with category detail
    public function top()
    {
        $books = Book::with('category')
                     ->withCount('loans')
                     ->orderByDesc('loans_count')
                     ->take(10)
                     ->get();

        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    // 📚 Get books by category id with category detail
    public function byCategory($category_id)
    {
        $books = Book::with('category')
                     ->where('category_id', $category_id)
                     ->get();

        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    // 📖 Get book detail by id with category detail
    public function show($id)
    {
        $book = Book::with('category')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $book
        ]);
    }
}
