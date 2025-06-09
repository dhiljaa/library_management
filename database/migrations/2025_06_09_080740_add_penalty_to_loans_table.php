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
        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('penalty', 10, 2)->default(0)->after('status'); // Denda dalam bentuk desimal
            $table->boolean('is_penalty_paid')->default(false)->after('penalty'); // Apakah denda sudah dibayar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'penalty')) {
                $table->dropColumn('penalty');
            }
            if (Schema::hasColumn('loans', 'is_penalty_paid')) {
                $table->dropColumn('is_penalty_paid');
            }
        });
    }
};
