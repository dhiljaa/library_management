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

    // Tambahkan properti appends agar 'rating' otomatis muncul di JSON/array
    protected $appends = ['rating'];

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

    /**
     * 🔥 Aksesor: Rata-rata rating dari semua review
     * Mengembalikan nilai float rata-rata rating atau 0 jika belum ada review.
     */
    public function getRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?? 0, 2);
    }
}
