<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel users dan books
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('book_id')
                ->constrained()
                ->onDelete('cascade');

            // Informasi waktu peminjaman dan pengembalian
            $table->timestamp('borrowed_at')->nullable();
            $table->timestamp('returned_at')->nullable();

            // Status peminjaman
            $table->enum('status', [
                'pending',   // Menunggu persetujuan
                'approved',  // Disetujui tapi belum diambil
                'borrowed',  // Buku sedang dipinjam
                'returned',  // Sudah dikembalikan
                'overdue',   // Lewat batas waktu
            ])->default('pending');

            $table->timestamps();

            // Index untuk mempercepat query pencarian berdasarkan user, book, dan status
            $table->index(['user_id', 'book_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
