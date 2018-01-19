<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DatabaseTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserInDatabase()
    {   
        $this->seeInDatabase('users', ['login' => 'root']);
        $this->assertTrue(true);
    }
    public function testMissingUserInDatabase()
    {   
        $this->notSeeInDatabase('users', ['login' => 'alsfors']);
        $this->assertTrue(true);
    }
    public function testSectionInDatabase()
    {   
        $this->seeInDatabase('sections', ['name' => 'Mój Hotel']);
        $this->assertTrue(true);
    }
    public function testMissingSectionInDatabase()
    {   
        $this->notSeeInDatabase('sections', ['name' => 'alsfors']);
        $this->assertTrue(true);
    }
    public function testItemInDatabase()
    {   
        $this->seeInDatabase('items', ['name' => 'Pomarańcze']);
        $this->assertTrue(true);
    }
    public function testMissingItemInDatabase()
    {   
        $this->notSeeInDatabase('items', ['name' => 'alsfors']);
        $this->assertTrue(true);
    }
    public function testTaskInDatabase()
    {   
        $this->seeInDatabase('tasks', ['name' => 'Testowy']);
        $this->assertTrue(true);
    }
    public function testMissingTaskInDatabase()
    {   
        $this->notSeeInDatabase('tasks', ['name' => 'alsfors']);
        $this->assertTrue(true);
    }

}
