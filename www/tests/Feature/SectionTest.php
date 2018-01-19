<?php

namespace Tests\Feature;

use App\Models\Section;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Tests\TestCaseWithPermission;
use Spatie\Permission\Models\Permission;

class SectionTest extends TestCaseWithPermission
{
    use WithoutMiddleware;

    public function testCreate()
    {

        $this
            // ->actingAs($this->user)
            // ->visit('/')
            // ->click('Działy')
            // ->seePageIs('/section')
            // ->click('Add')
            // ->seePageIs('/section/create/1')
            // ->type('test', 'name')
            // ->press('Zatwierdź')
            // ->seePageIs('/section')
        ;
    }

    // public function testDelete()
    // {   
    //     $sectionToDelete = Section::where('name', 'test')->first();

    //     $this
    //     ->actingAs($this->user)
    //     ->visit('/')
    //     ->click('Działy')
    //     ->seePageis('/section')
    //     ->call("DELETE", "/section/$sectionToDelete->id");
    // }
}
