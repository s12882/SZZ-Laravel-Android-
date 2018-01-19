<?php

namespace Tests\Unit;

use App\Models\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TaskTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreate()
    {
       $name = 'testowa';
       $description = 'testowy';
       $location = 'testowa';
       $section_id = '1';
       $scheduled_for = '2018-01-11';

       $task = Task::Create([
           'name' => $name,
           'description' => $description,
           'location' => $location,
           'section_id' => $section_id,
           'scheduled_for' => $scheduled_for
       ]);

       $this->assertTrue($task->name == $name);
       $this->assertTrue($task->description == $description);
       $this->assertTrue($task->location == $location);
       $this->assertTrue($task->section_id == $section_id);
       $this->assertTrue($task->scheduled_for == $scheduled_for);

       $task->forceDelete();

    }
}
