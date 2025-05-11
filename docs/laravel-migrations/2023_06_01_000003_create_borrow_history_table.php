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
            $table->string('user_email');
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->date('issue_date')->nullable();
            $table->decimal('fine', 10, 2)->nullable();
            $table->enum('status', ['Requested', 'Issued', 'Returned', 'Declined'])->default('Requested');
            $table->date('return_date')->nullable();
            $table->timestamps();
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