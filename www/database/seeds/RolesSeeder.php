<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      app()['cache']->forget('spatie.permission.cache');
      $role = Role::create(['name' => 'facility manager']);
      $role->givePermissionTo(Permission::all());

      $role1 = Role::create(['name' => 'section manager']);
      $role1->givePermissionTo('create user');
      $role1->givePermissionTo('update user');
      $role1->givePermissionTo('delete user');
      $role1->givePermissionTo('list users');
    }
}
