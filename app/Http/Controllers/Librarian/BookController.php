<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('category')
            ->paginate(15);

        return view('pustakawan.books.index', ['books' => $books]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('pustakawan.books.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'isbn' => 'nullable|string|unique:books',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'total_copies' => 'required|integer|min:1',
            'location' => 'nullable|string',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['available_copies'] = $validated['total_copies'];
        $validated['status'] = 'available';

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/book-covers'), $filename);
            $validated['cover_image'] = 'assets/book-covers/' . $filename;
        }

        Book::create($validated);

        return redirect()->route('librarian.books.index')->with('success', 'Buku berhasil dibuat.');
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('pustakawan.books.edit', ['book' => $book, 'categories' => $categories]);
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'total_copies' => 'required|integer|min:1',
            'location' => 'nullable|string',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'status' => 'required|in:available,unavailable,archived',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($book->cover_image && file_exists(public_path($book->cover_image))) {
                unlink(public_path($book->cover_image));
            }

            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/book-covers'), $filename);
            $validated['cover_image'] = 'assets/book-covers/' . $filename;
        }

        $book->update($validated);

        return redirect()->route('librarian.books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        // Delete cover image if exists
        if ($book->cover_image && file_exists(public_path($book->cover_image))) {
            unlink(public_path($book->cover_image));
        }
        
        $book->delete();
        return redirect()->route('librarian.books.index')->with('success', 'Buku berhasil dihapus.');
    }

    // Category Management Methods
    public function categories()
    {
        $categories = Category::paginate(15);
        return view('pustakawan.books.categories', ['categories' => $categories]);
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('librarian.books.categories')->with('success', 'Kategori berhasil dibuat.');
    }

    public function editCategory(Category $category)
    {
        return view('pustakawan.books.edit-category', ['category' => $category]);
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $category->update($validated);

        return redirect()->route('librarian.books.categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->books()->count() > 0) {
            return redirect()->route('librarian.books.categories')->with('error', 'Tidak bisa menghapus kategori yang memiliki buku.');
        }

        $category->delete();
        return redirect()->route('librarian.books.categories')->with('success', 'Kategori berhasil dihapus.');
    }
}
