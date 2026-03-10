<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $categories = [
            [
                'name'        => 'Administrative Staff',
                'description' => 'Office administration and support staff',
                'code'        => 'ADMIN',
                'is_active'   => true,
            ],
            [
                'name'        => 'Logistics Personnel',
                'description' => 'Logistics and supply chain staff',
                'code'        => 'LOG',
                'is_active'   => true,
            ],
            [
                'name'        => 'Medical Staff',
                'description' => 'Medical and healthcare personnel',
                'code'        => 'MED',
                'is_active'   => true,
            ],
            [
                'name'        => 'Technical Staff',
                'description' => 'IT and technical support staff',
                'code'        => 'TECH',
                'is_active'   => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['code' => $category['code']], $category);
        }

        // 1. SUPER ADMIN - Full access to ALL modules
        User::firstOrCreate(
            ['email' => 'superadmin@pgmc.com'],
            [
                'first_name'        => 'Super',
                'last_name'         => 'Admin',
                'username'          => 'superadmin',
                'password'          => Hash::make('superadmin12345'),
                'type'              => 'admin',
                'sex'               => 'male',
                'unit'              => 'COMMAND',
                'category_id'       => Category::where('code', 'ADMIN')->first()->id,
                'email_verified_at' => now(),
                'is_active'         => true,
            ]
        );

        // 2. PG4 ADMIN - Limited admin access
        User::firstOrCreate(
            ['email' => 'pg4admin@pgmc.com'],
            [
                'first_name'        => 'PG4',
                'last_name'         => 'Administrator',
                'username'          => 'pg4admin',
                'password'          => Hash::make('pg4admin123'),
                'type'              => 'admin',
                'sex'               => 'male',
                'unit'              => 'PG4',
                'category_id'       => Category::where('code', 'LOG')->first()->id,
                'email_verified_at' => now(),
                'is_active'         => true,
            ]
        );

        // 3. NORMAL USER
        User::firstOrCreate(
            ['email' => 'user@pgmc.com'],
            [
                'first_name'        => 'Regular',
                'last_name'         => 'User',
                'username'          => 'user',
                'password'          => Hash::make('user123'),
                'type'              => 'user',
                'sex'               => 'female',
                'unit'              => 'PG4',
                'category_id'       => Category::where('code', 'TECH')->first()->id,
                'email_verified_at' => now(),
                'is_active'         => true,
            ]
        );

        // 4. STUDENT DEMO
        User::firstOrCreate(
            ['email' => 'student@pgmc.com'],
            [
                'first_name'        => 'Student',
                'last_name'         => 'Demo',
                'username'          => 'student',
                'password'          => Hash::make('password'),
                'type'              => 'user',
                'sex'               => 'male',
                'unit'              => 'PG4',
                'category_id'       => Category::where('code', 'MED')->first()->id,
                'email_verified_at' => now(),
                'is_active'         => true,
            ]
        );

        $this->command->info('✓ Categories created successfully!');
        $this->command->info('');
        $this->command->info('=== USER ACCOUNTS CREATED ===');
        $this->command->info('1. SUPER ADMIN (Full Access):');
        $this->command->info('   Email: superadmin@pgmc.com');
        $this->command->info('   Password: superadmin12345');
        $this->command->info('   Access: ALL modules');
        $this->command->info('');
        $this->command->info('2. PG4 ADMIN (Limited Admin Access):');
        $this->command->info('   Email: pg4admin@pgmc.com');
        $this->command->info('   Password: pg4admin123');
        $this->command->info('   Access: Dashboard, Ordered Items, Inventory');
        $this->command->info('');
        $this->command->info('3. NORMAL USER:');
        $this->command->info('   Email: user@pgmc.com');
        $this->command->info('   Password: user123');
        $this->command->info('');
        $this->command->info('4. STUDENT DEMO:');
        $this->command->info('   Email: student@pgmc.com');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('✓ Total users created: ' . User::count());
    }
}