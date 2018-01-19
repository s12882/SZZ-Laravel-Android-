<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;
    $gender = $faker->randomElements(['male', 'female']);
    $name = $faker->firstName($gender);
    $surname = $faker->lastName($gender);
    return [
        'first_name' => $name,
        'surname' => $surname,
        'email' => mb_strtolower($name,'UTF-8').'.'.mb_strtolower($surname,'UTF-8').'@'.$faker->freeEmailDomain,
        'login' => $faker->userName,
        'phoneNumber' => $faker->phoneNumber,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
