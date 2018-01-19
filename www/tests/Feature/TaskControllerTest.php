<?php

namespace Tests\Feature;

use App\Controllers\TaskController;
use Tests\TestCaseWithPermission;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TaskControllerTest extends TestCaseWithPermission
{
    use WithoutMiddleware;

    public function testTaskController()
    {
        $response = $this
        ->actingAs($this->user)
        ->action('GET', 'TaskController@index');
        $this->assertEquals(200, $response->status());
    }
}
