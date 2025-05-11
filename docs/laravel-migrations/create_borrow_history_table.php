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
        Schema::create('borrow_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('user_email', 100); // Keeping for backward compatibility
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->date('issue_date')->nullable();
            $table->decimal('fine', 10, 2)->default(0.00);
            $table->enum('status', ['Requested', 'Issued', 'Returned', 'Declined'])->default('Requested');
            $table->date('return_date')->nullable();
            $table->timestamps(); // This will replace request_date with created_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_history');
    }
};