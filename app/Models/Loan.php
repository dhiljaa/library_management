<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Book;

class Loan extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi massal.
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'returned_at',
        'status',
        'penalty',
        'is_penalty_paid',
    ];

    /**
     * Casting atribut ke tipe data yang sesuai.
     */
    protected $casts = [
        'borrowed_at'     => 'datetime',
        'returned_at'     => 'datetime',
        'penalty'         => 'decimal:2',
        'is_penalty_paid' => 'boolean',
    ];

    /**
     * Relasi: Loan dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Loan terkait dengan satu Book.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Hitung denda berdasarkan keterlambatan pengembalian.
     *
     * Aturan:
     * - Peminjaman gratis selama 7 hari.
     * - Setelah 7 hari dikenakan denda Rp 2.000/hari.
     *
     * @return float Denda dalam rupiah.
     */
    public function calculatePenalty(): float
    {
        if (!$this->borrowed_at) {
            return 0.0;
        }

        $returnDate = $this->returned_at ?? Carbon::now();
        $daysBorrowed = $this->borrowed_at->diffInDays($returnDate);

        if ($daysBorrowed > 7) {
            $lateDays = $daysBorrowed - 7;
            return $lateDays * 2000;
        }

        return 0.0;
    }

    /**
     * Perbarui nilai denda dan simpan ke database.
     *
     * Sebaiknya dipanggil setiap kali status pinjaman berubah.
     */
    public function updatePenalty(): void
    {
        $this->penalty = $this->calculatePenalty();
        $this->save();
    }

    /**
     * Cek dan update status menjadi 'overdue' jika sudah lewat batas 7 hari.
     *
     * Panggil fungsi ini saat fetch atau update status pinjaman.
     */
    public function checkOverdue(): void
    {
        if ($this->status === 'borrowed' && $this->borrowed_at) {
            $dueDate = $this->borrowed_at->copy()->addDays(7);
            if (now()->greaterThan($dueDate)) {
                $this->status = 'overdue';
                $this->save();
            }
        }
    }
}
