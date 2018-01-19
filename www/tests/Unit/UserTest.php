<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $login = 'testowy';
        $first_name ='Robert';
        $surname ='Nowakowski';
        $email = 'testowywy@gmail.com';
        $phoneNumber ='123456789098';
        $password = \Hash::make('123456');
        $section_id = '1';


        $user = User::create([
            'login' => $login,
            'first_name' => $first_name,
            'surname' => $surname,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'password' => $password,
            'section_id' => $section_id

        ]);

        $this->assertTrue($user->login == $login);
        $this->assertTrue($user->first_name == $first_name);
        $this->assertTrue($user->surname == $surname);
        $this->assertTrue($user->email == $email);
        $this->assertTrue($user->phoneNumber == $phoneNumber);
        $this->assertTrue($user->password == $password);
        $this->assertTrue($user->section_id == $section_id);
    
        $user->forceDelete();
    }
        
}
