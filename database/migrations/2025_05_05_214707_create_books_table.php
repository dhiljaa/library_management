<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->year('published_year')->nullable();  // sesuaikan nama kolom di model juga ya
            $table->unsignedBigInteger('category_id');    // harus unsignedBigInteger
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('quantity')->default(0);
            $table->unsignedBigInteger('borrowed_count')->default(0);
            $table->timestamps();

            // foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
