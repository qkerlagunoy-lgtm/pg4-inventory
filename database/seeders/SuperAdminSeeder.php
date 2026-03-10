<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
       // Create Superadmin account


       User::updateOrCreate(
    ['email' => 'superadmin@pgmc.com'],
    [
        'first_name'        => 'Super',
        'last_name'         => 'Admin',
        'username'          => 'superadmin',
        'email'             => 'superadmin@pgmc.com',
        'password'          => Hash::make('superadmin12345'),
        'type'              => 'admin',
        'unit'              => 'COMMAND',
        'is_active'         => true,
        'email_verified_at' => now(), // ADD THIS
    ]
);
    }
}