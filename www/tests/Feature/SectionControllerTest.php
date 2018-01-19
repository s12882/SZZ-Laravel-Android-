<?php

namespace Tests\Feature;

use App\Controllers\SectionController;
use Tests\TestCaseWithPermission;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SectionControllerTest extends TestCaseWithPermission
{
    use WithoutMiddleware;

    public function testExample()
    {
        $response = $this
        ->actingAs($this->user)
        ->action('GET', 'SectionController@index');
        $this->assertEquals(200, $response->status());
    }
}
