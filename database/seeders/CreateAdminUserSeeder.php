<?php

namespace Database\Seeders;

use App\Models\RoleHasTranslatedPermissions;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->delete();
        \DB::table('users')->delete();

        $user = User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@app.com',
            'password' => bcrypt('12345678'),
            'roles_name' => ["SuperAdmin"],
            'status_name' => 'active',
        ]);

        $role = Role::create(['name' => 'SuperAdmin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
