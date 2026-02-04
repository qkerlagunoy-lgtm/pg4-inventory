<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default test user
        User::factory()->create([
            'first_name' => 'Test',
            'middle_name' => null,
            'last_name' => 'User',
            'suffix' => null,

            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),

            'sex' => 'male',
            'unit' => 'PG4',
            'type' => 'user',
        ]);

        // Call ItemSeeder (intentionally empty)
        $this->call([
            AdminUserSeeder::class,
            // ItemSeeder::class,
        ]);
    }
}
