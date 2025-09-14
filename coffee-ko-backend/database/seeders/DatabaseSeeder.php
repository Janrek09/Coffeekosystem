<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Sale;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ➤ Create Owner User
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@coffee-ko.test',
            'role' => 'owner',
            'password' => Hash::make('password123'),
        ]);

        // ➤ Create Employee User
        $employee = User::create([
            'name' => 'Barista',
            'email' => 'employee@coffee-ko.test',
            'role' => 'employee',
            'password' => Hash::make('password123'),
        ]);

        // ➤ Sample Inventories
        $beans = Inventory::create([
            'category' => 'Coffee',
            'name' => 'Espresso Beans',
            'unit' => 'kg',
            'stock' => 50,
            'threshold' => 10,
        ]);

        $arabica = Inventory::create([
            'category' => 'Coffee',
            'name' => 'Arabica Blend',
            'unit' => 'kg',
            'stock' => 30,
            'threshold' => 8,
        ]);

        $milk = Inventory::create([
            'category' => 'Ingredients',
            'name' => 'Milk (Liters)',
            'unit' => 'L',
            'stock' => 20,
            'threshold' => 5,
        ]);

        $sugar = Inventory::create([
            'category' => 'Ingredients',
            'name' => 'Sugar (kg)',
            'unit' => 'kg',
            'stock' => 15,
            'threshold' => 5,
        ]);

        $croissant = Inventory::create([
            'category' => 'Pastries',
            'name' => 'Croissant',
            'unit' => 'pcs',
            'stock' => 10,
            'threshold' => 3,
        ]);

        // ➤ Sample Sale
        Sale::create([
            'inventory_id' => $beans->id,
            'sold_by' => $employee->id,
            'quantity' => 2,
            'unit_price' => 150.00,
            'total_price' => 300.00,
        ]);
    }
}
