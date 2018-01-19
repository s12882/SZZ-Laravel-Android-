<?php

namespace App\Http\Controllers\API;

use App\Models\Task;
use App\Models\Item;
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

public function index(Request $request)
    {
        $tasks = Item::all();
        return response()->json(['success' => $tasks], 200);
    }

    /**
     * API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'name'=>'required',
        'type'=>'required',
        'amount'=>'required',
        'ean'=>'required',
        'category_id'=>'required',
        ]);

      if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);            
      }  

        if ($this->modelService->store($request)) {
            return response()->json(['success'=>'success'], 201);
        }
        return response()->json(['error'=>'user creating error'], 501);
    }

    /**
     * API
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $id = $request['id'];
            if($this->modelService->get($id)){
                return response()->json(['success'=>$this->modelService->get($id)], 200); 
            }
            return response()->json(['error'=>'Error - no such a task'], 501); 
        
    }

    /**
     * API
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'name'=>'required',
        'type'=>'required',
        'amount'=>'required',
        'ean'=>'required',
        'category_id'=>'required',
        ]);

      if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);            
      }  

       if ($this->modelService->update($request)) {
                return response()->json(['success'=>'success'], 200);
            }     

            return response()->json(['error'=>'task edit error'], 501); 
    }

    /**
     * API
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);            
            }  

             $id = $request['id'];

            if ($this->modelService->destroy($id)) {
                return response()->json(['success'=>'success'], 200);
            }
            return response()->json(['error'=>'task deliting error'], 401);
    }

    public function datatables(Request $request){
        return $this->modelService->datatables($request);
    }
}
