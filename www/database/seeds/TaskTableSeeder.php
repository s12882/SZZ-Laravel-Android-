<?php

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::create([
            'name' => 'Wymiana żarówki',
            'description' => 'Wyniama żarówki w garderobie',
            'location' => 'c115',
            'section_id' => '1'
          ]);
    }
}
