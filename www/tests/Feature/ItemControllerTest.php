<?php

namespace Tests\Feature;

use App\Controllers\ItemController;
use Tests\TestCaseWithPermission;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ItemControllerTest extends TestCaseWithPermission
{
    use WithoutMiddleware;

    public function testItemController()
    {
        $response = $this
        ->actingAs($this->user)
        ->action('GET', 'ItemController@index');
        $this->assertEquals(200, $response->status());
        $response = $this->call('GET', '/item');
        $this->assertEquals(200, $response->status());
    }
}
