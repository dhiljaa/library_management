<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Tambahkan hanya jika belum ada kolom category_id
            if (!Schema::hasColumn('books', 'category_id')) {
                $table->foreignId('category_id')->after('author')->constrained()->onDelete('cascade');
            }

            // Hapus kolom lama 'category' jika masih ada
            if (Schema::hasColumn('books', 'category')) {
                $table->dropColumn('category');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Rollback: Tambahkan kembali kolom 'category'
            if (!Schema::hasColumn('books', 'category')) {
                $table->string('category')->nullable();
            }

            // Hapus kolom category_id jika ada
            if (Schema::hasColumn('books', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
        });
    }
};
