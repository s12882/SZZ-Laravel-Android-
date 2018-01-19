<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{

    public function __construct(CommentService $commentService) {
        $this->modelService = $commentService;
        $this->commentModel = trans('models.comment');
    }

    public function store(CommentRequest $request)
    {
        return $this->modelService->store($request);
    }

    public function getComments(Request $request)
    {   
        return $this->modelService->getComments($request);
    }

    public function getNewComments(Request $request)
    {
        return $this->modelService->getNewComments($request);
    }

    public function destroy($id)
    {
        return $this->modelService->destroy($id);
    }

    public function datatables(Request $request){
        return $this->modelService->datatables($request);
    }
}
