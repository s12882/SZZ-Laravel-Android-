<?php

namespace App\Services;

use Auth;
use App\Models\Comment;
use Yajra\Datatables\Datatables;
use App\Helpers\DtButtonHelper;
use App\Enums\DtButtonType;

class CommentService {

    public function store($input)
    {   
        $input['author_id'] = Auth::id();
        $comment = Comment::create($input->except('_token'));
        $comment->save();
        return $comment->makeVisible('id');
    }

    public function destroy($id)
    {   
        return var_dump(Comment::findOrFail($id)->delete());
    }

    public function getComments($request)
    {
        $comments = Comment::where('task_id', $request->task_id)
                    ->with('author:id,first_name,surname')
                    ->orderBy('created_at')->get();
        return $comments;
    }

    public function getNewComments($request){
        $comments = Comment::where('task_id', $request->task_id)
                    ->where('id', '>', $request->last)
                    ->with('author:id,first_name,surname')
                    ->orderBy('created_at')->get();
        return $comments;
    }
}
