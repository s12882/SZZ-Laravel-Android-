<?php

namespace Tests\Unit;

use App\Models\Section;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SectionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
       $name = 'testowa';
       $left = '17';
       $right = '18';
       $parent_id = null;

       $section = Section::create([
           'name' => $name,
           'left' => $left,
           'right' => $right,
           'parent_id' => $parent_id
       ]);
       $this->assertTrue($section->name  == $name);
       $this->assertTrue($section->left == $left);
       $this->assertTrue($section->right == $right);
       $this->assertTrue($section->parent_id == $parent_id);

       $section->forceDelete();
    }
}
