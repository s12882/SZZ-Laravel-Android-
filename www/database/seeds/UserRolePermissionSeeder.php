<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $user = User::find(1);
      $user->assignRole('facility manager');
    }
}
