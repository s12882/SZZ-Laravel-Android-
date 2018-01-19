<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SectionSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UserRolePermissionSeeder::class);
        $this->call(ItemCategorySeeder::class);
        $this->call(ItemTableSeeder::class);
        $this->call(TaskTableSeeder::class);
    }
}
