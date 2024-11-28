<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'categories.index']);
        Permission::create(['name' => 'categories.create']);
        Permission::create(['name' => 'categories.edit']);
        Permission::create(['name' => 'categories.delete']);

        Permission::create(['name' => 'products.index']);
        Permission::create(['name' => 'products.create']);
        Permission::create(['name' => 'products.edit']);
        Permission::create(['name' => 'products.delete']);

        Permission::create(['name' => 'transactions.index']);
        Permission::create(['name' => 'transactions.create']);
        Permission::create(['name' => 'transactions.edit']);
        Permission::create(['name' => 'transactions.delete']);

        Permission::create(['name' => 'sales.index']);

        Permission::create(['name' => 'profits.index']);
        
    }
}
