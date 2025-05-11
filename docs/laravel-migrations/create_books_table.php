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
            $table->string('name', 255);
            $table->foreignId('author_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author', 100); // Keeping for backward compatibility
            $table->string('category', 50);
            $table->text('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('pdf')->nullable();
            $table->string('cover_image')->nullable();
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