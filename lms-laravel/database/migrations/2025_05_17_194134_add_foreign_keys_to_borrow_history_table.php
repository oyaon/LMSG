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
        Schema::table('borrow_history', function (Blueprint $table) {
            $table->foreign(['book_id'], 'borrow_history_ibfk_1')->references(['id'])->on('all_books')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrow_history', function (Blueprint $table) {
            $table->dropForeign('borrow_history_ibfk_1');
        });
    }
};
