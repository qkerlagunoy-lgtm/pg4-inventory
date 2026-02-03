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

        // Create super admin
        $superAdmin = User::create([
            'first_name' => 'Super',
            'middle_name' => 'User',
            'last_name' => 'Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('admin123'),
            'type' => 'admin',
            'sex' => 'male',
            'unit' => 'COMMAND',
            'category_id' => Category::first()->id,
            'email_verified_at' => now(),
        ]);

        // Create sample admin
        $admin = User::create([
            'first_name' => 'Regular',
            'middle_name' => 'System',
            'last_name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@afppgmc.com',
            'password' => Hash::make('admin123'),
            'type' => 'admin',
            'sex' => 'female',
            'unit' => 'LSO',
            'category_id' => Category::skip(1)->first()->id,
            'email_verified_at' => now(),
        ]);

        // Create sample users from different units
        $units = ['PG1', 'PG3', 'PG4', 'PG10', 'CUI', 'ISU', 'PAU', 'BDCU', 'PPBU'];
        
        foreach ($units as $index => $unit) {
            User::create([
                'first_name' => 'Sample',
                'middle_name' => 'User',
                'last_name' => 'From ' . $unit,
                'username' => 'user_' . strtolower($unit),
                'email' => 'user' . ($index + 1) . '@afppgmc.com',
                'password' => Hash::make('password123'),
                'type' => 'user',
                'sex' => $index % 2 == 0 ? 'male' : 'female',
                'unit' => $unit,
                'category_id' => Category::inRandomOrder()->first()->id,
                'email_verified_at' => now(),
            ]);
        }

        // Create the existing student user if not exists
        if (!User::where('email', 'student@gmail.com')->exists()) {
            User::create([
                'first_name' => 'Student',
                'last_name' => 'User',
                'username' => 'student',
                'email' => 'student@gmail.com',
                'password' => Hash::make('password'),
                'type' => 'user',
                'sex' => 'male',
                'unit' => 'PG4',
                'category_id' => Category::inRandomOrder()->first()->id,
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('✓ Categories created successfully!');
        $this->command->info('✓ Super Admin created: superadmin@gmail.com / admin123');
        $this->command->info('✓ Admin created: admin@afppgmc.com / admin123');
        $this->command->info('✓ Sample users created from different units');
        $this->command->info('✓ Total users created: ' . User::count());
    }
}