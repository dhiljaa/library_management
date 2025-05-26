<div class="mb-3">
    <label for="title" class="form-label">Judul Buku</label>
    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $book->title ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="author" class="form-label">Penulis</label>
    <input type="text" class="form-control" id="author" name="author" value="{{ old('author', $book->author ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="category" class="form-label">Kategori</label>
    <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $book->category ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Deskripsi</label>
    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $book->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="published_year" class="form-label">Tahun Terbit</label>
    <input type="number" class="form-control" id="published_year" name="published_year" value="{{ old('published_year', $book->published_year ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="quantity" class="form-label">Jumlah</label>
    <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', $book->quantity ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="image" class="form-label">Upload Gambar</label>
    <input type="file" class="form-control" id="image" name="image">
</div>

<div class="mb-3">
    <label for="image_url" class="form-label">URL Gambar (opsional jika tidak upload)</label>
    <input type="url" class="form-control" id="image_url" name="image_url" value="{{ old('image_url', $book->image_url ?? '') }}">
</div>

@if (!empty($book->image_url))
    <div class="mb-3">
        <label class="form-label">Preview Gambar</label><br>
        <img src="{{ asset($book->image_url) }}" alt="Book Image" width="150">
    </div>
@endif
