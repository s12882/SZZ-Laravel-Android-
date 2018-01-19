<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'acivate/deactivate users']);

        Permission::create(['name' => 'create section']);
        Permission::create(['name' => 'update section']);
        Permission::create(['name' => 'delete section']);
        Permission::create(['name' => 'list sections']);

        Permission::create(['name' => 'see all sections/users/tasks']);
        Permission::create(['name' => 'see own and slave sections/users/tasks']);

        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'delete role']);
        Permission::create(['name' => 'list roles']);

        Permission::create(['name' => 'create task']);
        Permission::create(['name' => 'update task']);
        Permission::create(['name' => 'delete task']);
        Permission::create(['name' => 'delete comment']);

        Permission::create(['name' => 'change role']);
        Permission::create(['name' => 'change section']);

        Permission::create(['name' => 'create item_category']);
        Permission::create(['name' => 'update item_category']);
        Permission::create(['name' => 'delete item_category']);

        Permission::create(['name' => 'create item']);
        Permission::create(['name' => 'update item']);
        Permission::create(['name' => 'delete item']);

        Permission::create(['name' => 'list warehouse_document']);
        Permission::create(['name' => 'delete warehouse_document']);
    }
}
