<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // 📚 Get all books
    public function index()
    {
        $books = Book::all();

        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    // 📈 Get top books
    public function top()
    {
        // Misalnya berdasarkan jumlah peminjaman (dengan relasi 'loans')
        $books = Book::withCount('loans')
                     ->orderByDesc('loans_count')
                     ->take(10)
                     ->get();

        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    // 📚 Get books by category
    public function byCategory($category)
    {
        $books = Book::where('category', $category)->get();

        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    // 📖 Get book detail
    public function show($id)
    {
        $book = Book::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $book
        ]);
    }
}
