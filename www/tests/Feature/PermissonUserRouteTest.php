<?php

namespace Tests\Feature;

use Tests\TestCaseWithPermission;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PermissonUserRouteTest extends TestCaseWithPermission
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserView()
    {
        $response = $this
        ->actingAs($this->user)
        ->call('GET', 'user');
        $this->seePageIs('user');
        $this->assertEquals(200, $response->status());
    }
    public function testUserEdit()
    {
        $response = $this
        ->actingAs($this->user)
        ->call('GET', 'user/1/edit');
        $this->seePageIs('user/1/edit');
        $this->assertEquals(200, $response->status());
    }
    public function testUserCreate()
    {
        $response = $this
        ->actingAs($this->user)
        ->call('GET', 'user/create');
        $this->seePageIs('user/create');
        $this->assertEquals(200, $response->status());
    }
    public function testSectionView()
    {
        $response = $this
        ->actingAs($this->user)
        ->call('GET', 'section');
        $this->seePageIs('section');
        $this->assertEquals(200, $response->status());
    }
    public function testSectionCreate()
    {
        $response = $this
        ->actingAs($this->user)
        ->call('GET', 'section/create');
        $this->seePageIs('section/create');
        $this->assertEquals(200, $response->status());
    }
    public function testItemCreate()
    {
        $response = $this
        ->actingAs($this->user)
        ->call('GET', 'item/create');
        $this->seePageIs('item/create');
        $this->assertEquals(200, $response->status());
    }
    public function testItemView()
    {
        $response = $this
        ->actingAs($this->user)
        ->call('GET', 'item');
        $this->seePageIs('item');
        $this->assertEquals(200, $response->status());
    }
    public function testRoleView()
    {
        $response = $this
        ->actingAs($this->user)
        ->call('GET', 'role');
        $this->seePageIs('role');
        $this->assertEquals(200, $response->status());
    }
    public function testRoleCreate()
    {
        $response = $this
        ->actingAs($this->user)
        ->call('GET', 'role/create');
        $this->seePageIs('role/create');
        $this->assertEquals(200, $response->status());
    }
    public function testTaskView()
    {
        $response = $this
        ->actingAs($this->user)
        ->call('GET', 'task');
        $this->seePageIs('task');
        $this->assertEquals(200, $response->status());
    }
    public function testTaskCreate()
    {
        $response = $this
        ->actingAs($this->user)
        ->call('GET', 'task/create');
        $this->seePageIs('task/create');
        $this->assertEquals(200, $response->status());
    }
}
