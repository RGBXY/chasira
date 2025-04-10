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

        Permission::create(['name' => 'roles.index']);
        Permission::create(['name' => 'roles.create']);
        Permission::create(['name' => 'roles.edit']);
        Permission::create(['name' => 'roles.delete']);

        Permission::create(['name' => 'employees.index']);
        Permission::create(['name' => 'employees.create']);
        Permission::create(['name' => 'employees.edit']);
        Permission::create(['name' => 'employees.delete']);

        Permission::create(['name' => 'stock_out.index']);
        Permission::create(['name' => 'stock_out.create']);
        Permission::create(['name' => 'stock_out.edit']);
        Permission::create(['name' => 'stock_out.delete']);

        Permission::create(['name' => 'stock_in.index']);
        Permission::create(['name' => 'stock_in.create']);
        Permission::create(['name' => 'stock_in.edit']);
        Permission::create(['name' => 'stock_in.delete']);

        Permission::create(['name' => 'suppliers.index']);
        Permission::create(['name' => 'suppliers.create']);
        Permission::create(['name' => 'suppliers.edit']);
        Permission::create(['name' => 'suppliers.delete']);

        Permission::create(['name' => 'stock_opname.index']);
        Permission::create(['name' => 'stock_opname.create']);
        Permission::create(['name' => 'stock_opname.edit']);
        Permission::create(['name' => 'stock_opname.delete']);

        Permission::create(['name' => 'customers.index']);
        Permission::create(['name' => 'customers.create']);
        Permission::create(['name' => 'customers.edit']);
        Permission::create(['name' => 'customers.delete']);

        Permission::create(['name' => 'discounts.index']);
        Permission::create(['name' => 'discounts.create']);
        Permission::create(['name' => 'discounts.edit']);
        Permission::create(['name' => 'discounts.delete']);
        
        Permission::create(['name' => 'coupon.index']);

        Permission::create(['name' => 'sales.index']);

        Permission::create(['name' => 'discount.index']);

        Permission::create(['name' => 'voucher.index']);

        Permission::create(['name' => 'profile.index']);

        Permission::create(['name' => 'transactions.index']);

        Permission::create(['name' => 'profits.index']);

        Permission::create(['name' => 'dashboard.index']);

        
    }
}
