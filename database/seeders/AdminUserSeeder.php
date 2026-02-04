<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // First, create some categories
        $categories = [
            [
                'name' => 'Administrative Staff',
                'description' => 'Office administration and support staff',
                'code' => 'ADMIN',
                'is_active' => true,
            ],
            [
                'name' => 'Logistics Personnel',
                'description' => 'Logistics and supply chain staff',
                'code' => 'LOG',
                'is_active' => true,
            ],
            [
                'name' => 'Medical Staff',
                'description' => 'Medical and healthcare personnel',
                'code' => 'MED',
                'is_active' => true,
            ],
            [
                'name' => 'Technical Staff',
                'description' => 'IT and technical support staff',
                'code' => 'TECH',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // 1. SUPER ADMIN - Full access to ALL modules
         $superAdmin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('admin123'),
            'type' => 'admin',
            'sex' => 'male',
            'unit' => 'COMMAND',
            'category_id' => Category::where('code', 'ADMIN')->first()->id,
            'email_verified_at' => now(),
        ]);;

        // 2. PG4 ADMIN - Limited admin access (dashboard, ordered items, inventory, users)
        $pg4Admin = User::create([
            'first_name' => 'PG4',
            'last_name' => 'Administrator',
            'username' => 'pg4admin',
            'email' => 'pg4admin@afppgmc.com',
            'password' => Hash::make('pg4admin123'),
            'type' => 'admin', // Still admin type but with limited access in your middleware/routes
            'sex' => 'male',
            'unit' => 'PG4',
            'category_id' => Category::where('code', 'LOG')->first()->id,
            'email_verified_at' => now(),
        ]);

        // 3. NORMAL USER - Regular user access (user dashboard, request items, ordered items)
        $normalUser = User::create([
            'first_name' => 'Regular',
            'last_name' => 'User',
            'username' => 'user',
            'email' => 'user@afppgmc.com',
            'password' => Hash::make('user123'),
            'type' => 'user',
            'sex' => 'female',
            'unit' => 'PG4',
            'category_id' => Category::where('code', 'TECH')->first()->id,
            'email_verified_at' => now(),
        ]);

        // Optional: Keep the student account for testing if needed
        $studentUser = User::create([
            'first_name' => 'Student',
            'last_name' => 'Demo',
            'username' => 'student',
            'email' => 'student@gmail.com',
            'password' => Hash::make('password'),
            'type' => 'user',
            'sex' => 'male',
            'unit' => 'PG4',
            'category_id' => Category::where('code', 'MED')->first()->id,
            'email_verified_at' => now(),
        ]);

        $this->command->info('✓ Categories created successfully!');
        $this->command->info('');
        $this->command->info('=== USER ACCOUNTS CREATED ===');
        $this->command->info('1. SUPER ADMIN (Full Access):');
        $this->command->info('   Email: superadmin@gmail.com');
        $this->command->info('   Password: admin123');
        $this->command->info('   Access: ALL modules');
        $this->command->info('');
        $this->command->info('2. PG4 ADMIN (Limited Admin Access):');
        $this->command->info('   Email: pg4admin@afppgmc.com');
        $this->command->info('   Password: pg4admin123');
        $this->command->info('   Access: Dashboard, Ordered Items, Inventory, Users');
        $this->command->info('');
        $this->command->info('3. NORMAL USER (Regular User):');
        $this->command->info('   Email: user@afppgmc.com');
        $this->command->info('   Password: user123');
        $this->command->info('   Access: User Dashboard, Request Items, Ordered Items');
        $this->command->info('');
        $this->command->info('4. STUDENT DEMO (For Testing):');
        $this->command->info('   Email: student@gmail.com');
        $this->command->info('   Password: password');
        $this->command->info('   Access: Same as Normal User');
        $this->command->info('');
        $this->command->info('✓ Total users created: ' . User::count());
    }
}