<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom quantity dan published_year ke tabel books.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->integer('quantity')->default(1);
            $table->integer('published_year')->nullable();
        });
    }

    /**
     * Rollback perubahan: hapus kolom quantity dan published_year.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'published_year']);
        });
    }
};
