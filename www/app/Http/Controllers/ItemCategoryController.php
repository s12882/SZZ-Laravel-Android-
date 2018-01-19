<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ItemCategoryService;
use App\Http\Requests\ItemCategoryRequest;

class ItemCategoryController extends Controller
{
    public function __construct(ItemCategoryService $workTypeService){
        $this->modelService = $workTypeService;
        $this->messageModel = trans('models.item_category');
    }
    
    public function index()
    {
        return View('item_category.index');
    }

    public function create()
    {
        $postAction = route('item_category.store');
        $actionMethod = 'POST';

        return View('item_category.edit', [
            'model' => null,
            'postAction' => $postAction,
            'actionMethod' => $actionMethod,
            'pageTitle' => 'Nowy rodzaj zadania'
        ]);
    }

    public function store(ItemCategoryRequest $request)
    {
        if ($this->modelService->store($request)) {
            return redirect()->route('item_category.index')->withSuccess(trans('actions.created_f', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_created_f', ['object' => $this->messageModel]));
    
    }

    public function show($id){}
        
    public function edit($id)
    {
        $item_category = $this->modelService->get($id);
        $postAction = route('item_category.update', ['item_category' => $id]);
        $actionMethod = 'PATCH';

        return view('item_category.edit', [
          'model' => $item_category,
          'postAction' => $postAction,
          'actionMethod' => $actionMethod,
          'pageTitle' => 'Edycja ' . $item_category->name
        ]);

    
    }

    public function update(ItemCategoryRequest $request)
    {
        if ($this->modelService->update($request)) {
            return redirect()->route('item_category.index')->withSuccess(trans('actions.updated_f',['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_updated_f',['object' => $this->messageModel]));
    }

    public function destroy($id)
    {
        if ($this->modelService->destroy($id)) {
            return redirect()->route('item_category.index')->withSuccess(trans('actions.deleted_f',['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_deleted_f',['object' => $this->messageModel]));
    }

    public function datatables(Request $request) {
        return $this->modelService->datatables($request);
    }
}
