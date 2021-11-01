<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_role = Role::create(['name' => 'super']);
        $super_user = User::find(1);
        $super_user->assignRole($super_role);
        $role_permission = [
            'viewer' => 'view todo',
            'creator' => 'create todo',
            'editor' => 'edit todo',
            'deletor' => 'delete todo',
        ];
        $index = 2;
        foreach ($role_permission as $key => $value) {
            $role = Role::create(['name' => $key]);
            $permission = Permission::create(['name' => $value]);
            $user = User::find($index++);
            $role->givePermissionTo($permission);
            $user->assignRole($role);
        }
    }
}
