<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ItemService;
use App\Services\ItemCategoryService;
use App\Http\Requests\ItemRequest;

class ItemController extends Controller
{

  public function __construct(ItemService $itemService, ItemCategoryService $itemCategoryService) {
    $this->modelService = $itemService;
    $this->itemCategoryService = $itemCategoryService;
    $this->messageModel = trans('models.item');
}

    public function index()
    {
        return view('item.index', [
            'categories' => $this->itemCategoryService->lists(),
        ]);
    }

    public function create()
    {
        return view('item.edit', [
            'pageTitle' => 'Nowy przedmiot',
            'categories' => $this->itemCategoryService->lists(),
            'model' => null,
            'postAction' => route('item.store'),
            'actionMethod' => 'POST'
        ]);
    }

    public function store(ItemRequest $request)
    {
        if ($this->modelService->store($request)) {
            return redirect()->route('item.index')->withSuccess(trans('actions.created', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_created', ['object' => $this->messageModel]));
    }

    public function edit($id)
    {
        $item = $this->modelService->get($id);
        $postAction = route('item.update', ['item' => $item->id]);
        $actionMethod = 'PATCH';
        return view('item.edit', [
            'model' => $item,
            'postAction' => $postAction,
            'categories' => $this->itemCategoryService->lists(),
            'actionMethod' => $actionMethod,
            'pageTitle' => 'Edycja ' . $item->name
          ]);
    }

    public function update(ItemRequest $request)
    {
        if($this->modelService->update($request)) {
            return redirect()->route('item.index')->withSuccess(trans('actions.updated', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_updated', ['object' => $this->messageModel]));
    }

    public function destroy($id)
    {
        if ($this->modelService->destroy($id)) {
            return redirect()->route('item.index')->withSuccess(trans('actions.deleted', ['object' => $this->messageModel]));
        }
      return back()->withErrors(trans('actions.not_deleted', ['object' => $this->messageModel]));
    }

    public function datatables(Request $request){
        return $this->modelService->datatables($request);
    }
}
