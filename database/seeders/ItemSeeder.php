<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        Item::create([
            'category' => 'IT Equipment',
            'item_name' => 'Laptop',
            'description' => 'Dell Latitude 15" Laptop',
            'available_quantity' => 5,
        ]);

        Item::create([
            'category' => 'Office Supplies',
            'item_name' => 'Printer Paper',
            'description' => 'A4 Size, 500 sheets per ream',
            'available_quantity' => 50,
        ]);

        Item::create([
            'category' => 'IT Equipment',
            'item_name' => 'Mouse',
            'description' => 'Wireless optical mouse',
            'available_quantity' => 20,
        ]);

        Item::create([
            'category' => 'IT Equipment',
            'item_name' => 'Keyboard',
            'description' => 'Mechanical keyboard',
            'available_quantity' => 15,
        ]);

        Item::create([
            'category' => 'Office Supplies',
            'item_name' => 'Ballpen',
            'description' => 'Blue ink, box of 12',
            'available_quantity' => 100,
        ]);

        Item::create([
            'category' => 'Furniture',
            'item_name' => 'Office Chair',
            'description' => 'Ergonomic office chair',
            'available_quantity' => 8,
        ]);
    }
}