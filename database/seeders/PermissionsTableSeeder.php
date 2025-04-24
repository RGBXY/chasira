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
        // Permission Categories
        Permission::create([
            'name' => 'categories.index',
            'description' => 'Izin untuk mengakses halaman categories'
        ]);
        Permission::create([
            'name' => 'categories.create',
            'description' => 'Izin untuk membuat data categories'
        ]);
        Permission::create([
            'name' => 'categories.edit',
            'description' => 'Izin untuk mengedit data categories'
        ]);
        Permission::create([
            'name' => 'categories.delete',
            'description' => 'Izin untuk menghapus data categories'
        ]);

        // Permission Product
        Permission::create([
            'name' => 'products.index',
            'description' => 'Izin untuk mengakses halaman products'
        ]);
        Permission::create([
            'name' => 'products.create',
            'description' => 'Izin untuk membuat data products'
        ]);
        Permission::create([
            'name' => 'products.edit',
            'description' => 'Izin untuk mengedit data products'
        ]);
        Permission::create([
            'name' => 'products.delete',
            'description' => 'Izin untuk menghapus data products'
        ]);
        
        // Permission Roles
        Permission::create([
            'name' => 'roles.index',
            'description' => 'Izin untuk mengakses halaman roles'
        ]);
        Permission::create([
            'name' => 'roles.create',
            'description' => 'Izin untuk membuat data roles'
        ]);
        Permission::create([
            'name' => 'roles.edit',
            'description' => 'Izin untuk mengedit data roles'
        ]);
        Permission::create([
            'name' => 'roles.delete',
            'description' => 'Izin untuk menghapus data roles'
        ]);

        // Permission Employees
        Permission::create([
            'name' => 'employees.index',
            'description' => 'Izin untuk mengakses halaman employees'
        ]);
        Permission::create([
            'name' => 'employees.create',
            'description' => 'Izin untuk membuat data employees'
        ]);
        Permission::create([
            'name' => 'employees.edit',
            'description' => 'Izin untuk mengedit data employees'
        ]);
        Permission::create([
            'name' => 'employees.delete',
            'description' => 'Izin untuk menghapus data employees'
        ]);

        // Permission Stock_out
        Permission::create([
            'name' => 'stock_out.index',
            'description' => 'Izin untuk mengakses halaman stock_out'
        ]);
        Permission::create([
            'name' => 'stock_out.create',
            'description' => 'Izin untuk membuat data stock_out'
        ]);

        // Permission Stock_in
        Permission::create([
            'name' => 'stock_in.index',
            'description' => 'Izin untuk mengakses halaman stock_in'
        ]);
        Permission::create([
            'name' => 'stock_in.create',
            'description' => 'Izin untuk membuat data stock_in'
        ]);

        // Permission Stock_opname
        Permission::create([
            'name' => 'stock_opname.index',
            'description' => 'Izin untuk mengakses halaman stock_opname'
        ]);
        Permission::create([
            'name' => 'stock_opname.create',
            'description' => 'Izin untuk membuat data stock_opname'
        ]);

        // Permission Suppliers
        Permission::create([
            'name' => 'suppliers.index',
            'description' => 'Izin untuk mengakses halaman suppliers'
        ]);
        Permission::create([
            'name' => 'suppliers.create',
            'description' => 'Izin untuk membuat data suppliers'
        ]);
        Permission::create([
            'name' => 'suppliers.edit',
            'description' => 'Izin untuk mengedit data suppliers'
        ]);
        Permission::create([
            'name' => 'suppliers.delete',
            'description' => 'Izin untuk menghapus data suppliers'
        ]);

        // Permission Suppliers
        Permission::create([
            'name' => 'customers.index',
            'description' => 'Izin untuk mengakses halaman customers'
        ]);
        Permission::create([
            'name' => 'customers.create',
            'description' => 'Izin untuk membuat data customers'
        ]);
        Permission::create([
            'name' => 'customers.edit',
            'description' => 'Izin untuk mengedit data customers'
        ]);
        Permission::create([
            'name' => 'customers.delete',
            'description' => 'Izin untuk menghapus data customers'
        ]);

        // Permission Discounts
        Permission::create([
            'name' => 'discounts.index',
            'description' => 'Izin untuk mengakses halaman discounts'
        ]);
        Permission::create([
            'name' => 'discounts.create',
            'description' => 'Izin untuk membuat data discounts'
        ]);
        Permission::create([
            'name' => 'discounts.edit',
            'description' => 'Izin untuk mengedit data discounts'
        ]);
        Permission::create([
            'name' => 'discounts.delete',
            'description' => 'Izin untuk menghapus data discounts'
        ]);
        
        // Permission Sales
        Permission::create([
            'name' => 'sales.index',
            'description' => 'Izin untuk mengakses halaman sales'
        ]);

        // Permission Sales
        Permission::create([
            'name' => 'transactions.index',
            'description' => 'Izin untuk mengakses halaman transactions'
        ]);

        // Permission Profits
        Permission::create([
            'name' => 'profits.index',
            'description' => 'Izin untuk mengakses halaman profits'
        ]);

        // Permission Dashboard
        Permission::create([
            'name' => 'dashboard.index',
            'description' => 'Izin untuk mengakses halaman dashboard'
        ]);

        
    }
}
