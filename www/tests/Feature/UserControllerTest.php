<?php

namespace Tests\Feature;

use App\Controllers\UserController;
use Tests\TestCaseWithPermission;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserControllerTest extends TestCaseWithPermission
{
    use WithoutMiddleware;

    public function testUserController()
    {
        $response = $this
        ->actingAs($this->user)
        ->action('GET', 'UserController@index');
        $this->assertEquals(200, $response->status());
    }
    
}
