<?php

namespace Tests\Unit;

use App\Models\Comment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $content = 'test';
        $task_id = '1';
        $author_id = '1';


        $comment = Comment::create([
            'content' => $content,
            'task_id' => $task_id,
            'author_id' => $author_id
        ]);

        $this->assertTrue($comment->content == $content);
        $this->assertTrue($comment->task_id == $task_id);
        $this->assertTrue($comment->author_id == $author_id);
      
        $comment->forceDelete();
    }
}
