<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Tests\TestCaseWithPermission;
use Spatie\Permission\Models\Permission;

class ItemTest extends TestCaseWithPermission
{
   Use WithoutMiddleware;

    public function testCreate()
    {
        $this
        ->actingAs($this->user)
        ->visit('/')
        ->click('Przedmioty')
        ->seePageIs('/item')
        ->click('Dodaj')
        ->seePageIs('/item/create')
        ->type('Papier Toaletowy', 'name')
        ->type('Chemia', 'type')
        ->type('111', 'amount')
        ->type('12345678', 'ean')
        ->press('Zatwierdź')
        ->seePageIs('/item')
        ;
    }
    
    public function testDelete()
    {   
        $itemToDelete = Item::where('name', 'Papier Toaletowy')->first();

        $this
        ->actingAs($this->user)
        ->visit('/')
        ->click('Przedmioty')
        ->seePageis('/item')
        ->call("DELETE", "/item/$itemToDelete->id");
    }
}
