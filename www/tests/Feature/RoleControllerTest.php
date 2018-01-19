<?php

namespace Tests\Feature;

use App\Controllers\RoleController;
use Tests\TestCaseWithPermission;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoleControllerTest extends TestCaseWithPermission
{
    use WithoutMiddleware;

    public function testRoleControllerIndex()
    {
        $response = $this
        ->actingAs($this->user)
        ->action('GET', 'RoleController@index');
        $this->assertEquals(200, $response->status());
        $response = $this->call('GET', '/role');
        $this->assertEquals(200, $response->status());
    }
    public function testRoleControllerCreate()
    {
        $response = $this
        ->actingAs($this->user)
        ->action('GET', 'RoleController@create');
        $this->assertEquals(200, $response->status());
        $response = $this->call('GET', 'role/create');
        $this->assertEquals(200, $response->status());
    }
}
