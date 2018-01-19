<?php

namespace Tests\Unit;

use App\Models\Item;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ItemTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreate()
    {
        $name = 'przedmiot';
        $type = 'przedmiot';
        $amount = '1';
        $ean = '12345678';


        $item = Item::create([
            'name' => $name,
            'type' => $type,
            'amount' => $amount,
            'ean' => $ean
        ]);
    

    $this->assertTrue($item->name == $name);
    $this->assertTrue($item->type == $type);
    $this->assertTrue($item->amount == $amount);
    $this->assertTrue($item->ean == $ean);
    
    $item->forceDelete();
    }
}

