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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('user_email', 100); // Keeping for backward compatibility
            $table->string('book_ids'); // Comma-separated list of book IDs
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('transaction_id', 100)->nullable();
            $table->enum('payment_status', ['Pending', 'Completed', 'Failed'])->default('Completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};