<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRouteTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserView()
    {
        $response = $this->call('GET', 'user');
        $this->assertRedirectedTo('/login');
        $this->assertEquals(302, $response->status());
    }
    
    public function testUserCreate()
    {
        $response = $this->call('GET', 'user/create');
        $this->assertRedirectedTo('/login');
        $this->assertEquals(302, $response->status());
    }
    public function testUserEdit()
    {
        $response = $this->call('GET', 'user/1/edit');
        $this->assertRedirectedTo('/login');
        $this->assertEquals(302, $response->status());
    }
    public function testSectionView()
    {
        $response = $this->call('GET', 'section');
        $this->assertRedirectedTo('/login');
        $this->assertEquals(302, $response->status());
    }
    public function testSectionCreate()
    {
        $response = $this->call('GET', 'section/create');
        $this->assertRedirectedTo('/login');
        $this->assertEquals(302, $response->status());
    }
    public function testItemCreate()
    {
        $response = $this->call('GET', 'item/create');
        $this->assertRedirectedTo('/login');
        $this->assertEquals(302, $response->status());
    }
    public function testItemView()
    {
        $response = $this->call('GET', 'item');
        $this->assertRedirectedTo('/login');
        $this->assertEquals(302, $response->status());
    }
    public function testRoleView()
    {
        $response = $this->call('GET', 'role');
        $this->assertRedirectedTo('/login');
        $this->assertEquals(302, $response->status());
    }
    public function testRoleCreate()
    {
        $response = $this->call('GET', 'role/create');
        $this->assertRedirectedTo('/login');
        $this->assertEquals(302, $response->status());
    }
    public function testTaskView()
    {
        $response = $this->call('GET', 'task');
        $this->assertRedirectedTo('/login');
        $this->assertEquals(302, $response->status());
    }
    public function testTaskCreate()
    {
        $response = $this->call('GET', 'task/create');
        $this->assertRedirectedTo('/login');
        $this->assertEquals(302, $response->status());
    }
}
