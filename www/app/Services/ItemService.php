<?php

namespace App\Services;

use App\Models\Item;
use Yajra\Datatables\Datatables;
use App\Helpers\DtButtonHelper;
use App\Enums\DtButtonType;

class ItemService {

    public function get($id) {
        return Item::findOrFail($id);
    }

    public function lists() {
        return Item::orderBy('name')->select('name', 'id')->pluck('name', 'id');
    }

    public function store($input) {
        return Item::create($input->except('_token'));
    }

    public function update($input) {
      return Item::findOrFail($input['id'])->update($input->except(['id']));
    }

    public function destroy($id) {
        return Item::findOrFail($id)->delete();
    }
    
    public function datatables($input) {
        $query = Item::select()->with('category:id,name');
        $datatables = Datatables::of($query)
                ->addColumn('actions', function($item) {
                    $actions = '';
                    if(\Auth::user()->hasPermissionTo('update item'))
                        $actions .= DtButtonHelper::getByType(route('item.edit', ['item' => $item->id]), DtButtonType::EDIT);
                    if(\Auth::user()->hasPermissionTo('delete item'))
                        $actions .= DtButtonHelper::getByType(route('item.destroy', ['item' => $item->id]), DtButtonType::DELETE);
                    return $actions;
                })->rawColumns(['actions']);
       
                
        if ($input['amount_from'])
            $datatables->where('amount', '>=', $input['amount_from']);
        
        if ($input['amount_to'])
            $datatables->where('amount', '<=', $input['amount_to']);
        
        if ($input['category_id'])
            $datatables->where('category_id', '=',  $input['category_id']);

         return $datatables->make(true);
    }

}
