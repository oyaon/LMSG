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
            $table->integer('id', true);
            $table->string('user_email', 100);
            $table->integer('book_id')->index('book_id');
            $table->date('issue_date')->nullable();
            $table->decimal('fine', 10)->default(0);
            $table->enum('status', ['Requested', 'Issued', 'Returned', 'Declined'])->default('Requested')->index('idx_borrow_status');
            $table->timestamp('request_date')->useCurrent();
            $table->date('return_date')->nullable();
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
