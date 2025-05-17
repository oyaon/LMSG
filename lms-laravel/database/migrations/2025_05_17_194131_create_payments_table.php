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
            $table->integer('id', true);
            $table->string('user_email', 100)->index('idx_payment_user');
            $table->string('book_ids');
            $table->decimal('amount', 10);
            $table->date('payment_date');
            $table->string('transaction_id', 100)->nullable();
            $table->enum('payment_status', ['Pending', 'Completed', 'Failed'])->default('Completed');
            $table->timestamp('created_at')->useCurrent();
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
