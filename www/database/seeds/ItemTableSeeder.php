<?php

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::create([
            'name' => 'Żarówka LED',
            'type' => 'GU 10 ',
            'amount' => '25',
            'ean' => '5905339245038',
            'category_id' => 1
          ]);

    }
}
