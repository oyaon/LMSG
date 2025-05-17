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
        Schema::table('all_books', function (Blueprint $table) {
            $table->foreign(['author_id'], 'all_books_ibfk_1')->references(['id'])->on('authors')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('all_books', function (Blueprint $table) {
            $table->dropForeign('all_books_ibfk_1');
        });
    }
};
