<?php

namespace App\Http\Controllers;

use App\Enums\WarehouseDocumentCategory;
use Illuminate\Http\Request;
use App\Services\WarehouseDocumentService;
use App\Services\TaskService;

class WarehouseDocumentController extends Controller
{
    public function __construct(WarehouseDocumentService $warehouseDocumentService, TaskService $taskService){
        $this->modelService = $warehouseDocumentService;
        $this->taskService = $taskService;
        $this->messageModel = trans('models.warehouse_document');
    }

    public function index() {
        return View('warehouse_document.index',[
            'documentCategories' => WarehouseDocumentCategory::getSelect()
        ]);
    }

    public function create(Request $request) {
        $postAction = route('warehouse_document.store');
        $actionMethod = 'POST';

        return view('warehouse_document.edit', [
          'model' => null,
          'postAction' => $postAction,
          'actionMethod' => $actionMethod,
          'pageTitle' => 'Nowy dokument',
          'categories' => WarehouseDocumentCategory::getSelect(),
          'assignedItems' => '[]',
          'task_id' => $request->task,
          'tasks' => $this->taskService->lists()
        ]);
    }

    public function store(Request $request)
    {
        if ($this->modelService->store($request)) {
            return back()->withSuccess(trans('actions.created', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_created', ['object' => $this->messageModel]));
    }

    public function destroy($id) {
        if ($this->modelService->destroy($id)) {
            return redirect()->route('warehouse_document.index')->withSuccess(trans('actions.deleted', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_deleted', ['object' => $this->messageModel]));
    }

    // public function restore($id) {
    //     if ($this->modelService->restore($id)) {
    //         return redirect()->route('warehouse_document.index')->withSuccess(trans('actions.restored', ['object' => $this->messageModel]));
    //     }
    //     return back()->withErrors(trans('actions.not_restored', ['object' => $this->messageModel]));
    // }

    public function datatables(Request $request) {
        return $this->modelService->datatables($request);
    }
}
