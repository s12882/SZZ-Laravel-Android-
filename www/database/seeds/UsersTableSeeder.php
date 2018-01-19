<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
      User::create([
          'login' => 'root',
          'email' => 'lukasz.grabowski6@gmail.com',
          'password' => \Hash::make('inz2018'),
          'first_name' => 'Łukasz',
          'surname' => 'Grabowski',
          'phoneNumber' => '555555555'
        ]);

        $users = factory(App\Models\User::class, 6)->create();
      }
}
