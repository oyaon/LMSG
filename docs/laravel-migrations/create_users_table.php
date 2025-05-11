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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('user_name', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->tinyInteger('user_type')->default(1); // 0: Admin, 1: Regular User
            $table->rememberToken(); // Added for Laravel auth
            $table->timestamps(); // This will create created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};