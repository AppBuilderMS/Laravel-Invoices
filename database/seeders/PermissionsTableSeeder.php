<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permissions')->delete();

        $permissions = [
            'Invoices',
            'Invoices list',
            'Paid invoices',
            'Partially paid invoices',
            'Unpaid invoices',
            'Invoices archive',
            'Reports',
            'Invoices report',
            'Customers report',
            'Users',
            'Users list',
            'Users roles',
            'Users permissions',
            'settings',
            'Products',
            'Departments',

            'Add an invoice',
            'Delete an invoice',
            'Export Excel',
            'Change payment status',
            'Edit an invoice',
            'Archive an invoice',
            'Print an invoice',
            'Add an attachment',
            'Delete an attachment',

            'Add a user',
            'Edit a user',
            'Delete a user',

            'Show role',
            'Add role',
            'Edit role',
            'Delete role',

            'Show permission',
            'Add permission',
            'Edit permission',
            'Delete permission',

            'Add a product',
            'Edit a product',
            'Delete a product',

            'Add a department',
            'Edit a department',
            'Delete a department',
            'Notifications',
        ];
        foreach ($permissions as $permission)
        {
            Permission::create(['name' => $permission]);
        }
    }
}
