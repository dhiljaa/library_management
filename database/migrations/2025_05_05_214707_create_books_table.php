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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable(); // ✅ kolom publisher
            $table->year('year')->nullable();        // ✅ kolom year
            $table->string('category');
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);    // ✅ disesuaikan dari 'quantity' ke 'stock'
            $table->unsignedBigInteger('borrowed_count')->default(0); // top books
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
