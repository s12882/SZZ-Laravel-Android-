<?php

namespace Tests\Feature;

use Tests\TestCaseWithPermission;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCaseWithPermission
{   

    public function testBasicTest()
    {  
        $response = $this->visit('/login')
        ->type($this->user->login, 'login')
        ->type('secret', 'password')
        ->press('Zaloguj')
        ->seePageIs('Strona główna');
    }
}
