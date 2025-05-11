<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user if it doesn't exist
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'user_name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'user_type' => 0, // Admin
            ]);
        }

        // Import existing users from the old database
        $oldUsers = DB::connection('mysql_old')
            ->table('users')
            ->get();

        foreach ($oldUsers as $oldUser) {
            // Skip if user already exists
            if (User::where('email', $oldUser->email)->exists()) {
                continue;
            }

            User::create([
                'first_name' => $oldUser->first_name,
                'last_name' => $oldUser->last_name,
                'user_name' => $oldUser->user_name,
                'email' => $oldUser->email,
                'password' => $oldUser->password, // Assuming password is already hashed
                'user_type' => $oldUser->user_type,
                'created_at' => $oldUser->created_at ?? now(),
                'updated_at' => $oldUser->updated_at ?? now(),
            ]);
        }
    }
}