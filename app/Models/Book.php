<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'published_year',   // Tahun terbit
        'category_id',      // Foreign key ke tabel categories
        'description',
        'image_url',
        'quantity',         // Jumlah stok buku
        'borrowed_count',
    ];

    /**
     * Relasi: Satu buku dimiliki oleh satu kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: Satu buku bisa memiliki banyak peminjaman
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Relasi: Satu buku bisa memiliki banyak review
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
