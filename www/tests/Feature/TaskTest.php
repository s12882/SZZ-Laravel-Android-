<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Tests\TestCaseWithPermission;
use Spatie\Permission\Models\Permission;

class TaskTest extends TestCaseWithPermission
{
    Use WithoutMiddleware;

    public function testCreate()
    {
        $this
        ->actingAs($this->user)
        ->visit('/')
        ->click('Zadania')
        ->seePageIs('/task')
        ->click('Dodaj')
        ->seePageIs('/task/create')
        ->type('Testowa', 'name')
        ->type('asd', 'location')
        ->type('Chemia', 'description')
        ->type('2018-01-10','scheduled_for')
        ->select('1', 'section_id')
        ->press('Zatwierdź')
        ->seePageIs('/task')
        ;
    }

    public function testDelete()
    {
        $taskToDelete = Task::where('name', 'Testowa')->first();

        $this
        ->actingAs($this->user)
        ->visit('/')
        ->click('Zadania')
        ->seePageis('/task')
        ->call("DELETE", "/task/$taskToDelete->id");
    }
}
