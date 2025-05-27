<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // jenis notifikasi
            $table->string('title'); // judul singkat
            $table->text('message')->nullable(); // isi pesan
            $table->boolean('is_read')->default(false); // apakah sudah dibaca

            // Relasi ke user dan book, nullable karena tidak semua notif wajib terkait keduanya
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->nullable()->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
