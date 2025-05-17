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
        Schema::create('all_books', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name')->index('idx_book_name');
            $table->integer('author_id')->nullable()->index('author_id');
            $table->string('author', 100)->index('idx_book_author');
            $table->string('category', 50)->index('idx_book_category');
            $table->text('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price', 10)->default(0);
            $table->string('pdf')->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('all_books');
    }
};
