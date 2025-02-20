<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123123'),
        ]);

        $adminPermissions = Permission::all();
        $cashierPermissions = Permission::whereIn('name', [
            'transactions.index',
        ])->get();

        $admin = Role::find(1);
        $cashier = Role::find(2);

        $admin->syncPermissions($adminPermissions);
        $cashier->syncPermissions($cashierPermissions);

        $user->assignRole($admin);
    }
}
