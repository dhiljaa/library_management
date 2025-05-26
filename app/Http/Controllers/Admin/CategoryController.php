<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        if ($request->wantsJson()) {
            // Kalau request dari API (expect JSON)
            return response()->json(['data' => $categories]);
        }

        // Kalau request dari browser (Blade view)
        return view('admin.categories.index', compact('categories'));
    }

    public function create(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Method not allowed'], 405);
        }
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name',
        ]);

        $category = Category::create($validated);

        if ($request->wantsJson()) {
            return response()->json($category, 201);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Request $request, Category $category)
    {
        if ($request->wantsJson()) {
            return response()->json($category);
        }

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name,' . $category->id,
        ]);

        $category->update($validated);

        if ($request->wantsJson()) {
            return response()->json($category);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Request $request, Category $category)
    {
        $category->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Category deleted successfully']);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
