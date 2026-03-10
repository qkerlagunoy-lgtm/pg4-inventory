<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default test user
        User::factory()->create([
            'first_name'  => 'Test',
            'middle_name' => null,
            'last_name'   => 'User',
            'suffix'      => null,
            'username'    => 'testuser',
            'email'       => 'test@pgmc.com',
            'password'    => Hash::make('password'),
            'sex'         => 'male',
            'unit'        => 'PG4',
            'type'        => 'user',
        ]);

        $this->call([
            AdminUserSeeder::class,
        ]);
    }
}