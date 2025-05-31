<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $query = Book::with('category')
                    ->withAvg('reviews', 'rating'); // Ambil rata-rata rating review

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $books = $query->orderBy('title')->paginate(10);
        $books->appends([
            'search' => $search,
            'category_id' => $categoryId,
        ]);

        $categories = Category::all();

        return view('admin.books.index', compact('books', 'search', 'categoryId', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'published_year' => 'required|integer|min:1000|max:' . date('Y'),
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_url' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('books', 'public');
            $validated['image_url'] = 'storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $validated['image_url'] = $request->image_url;
        }

        $validated['borrowed_count'] = 0;

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'published_year' => 'sometimes|integer|min:1000|max:' . date('Y'),
            'category_id' => 'sometimes|exists:categories,id',
            'description' => 'nullable|string',
            'quantity' => 'sometimes|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_url' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            // Hapus file lama jika ada
            if ($book->image_url && str_starts_with($book->image_url, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $book->image_url));
            }

            $path = $request->file('image')->store('books', 'public');
            $validated['image_url'] = 'storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $validated['image_url'] = $request->image_url;
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Hapus file gambar lama jika ada
        if ($book->image_url && str_starts_with($book->image_url, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $book->image_url));
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }

    public function show($id)
    {
        $book = Book::with('category')
                    ->withAvg('reviews', 'rating') // Ambil rata-rata rating review
                    ->findOrFail($id);

        return view('admin.books.show', compact('book'));
    }

    public function popular()
    {
        $popular_books = Book::with('category')
            ->withCount('loans')
            ->withAvg('reviews', 'rating') // Ambil rata-rata rating review
            ->orderByDesc('loans_count')
            ->paginate(3);

        $books = Book::with('category')
            ->withAvg('reviews', 'rating') // Ambil rata-rata rating review
            ->paginate(10);

        return view('admin.dashboard', compact('popular_books', 'books'));
    }
}
