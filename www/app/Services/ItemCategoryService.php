<?php

namespace App\Services;

use App\Models\ItemCategory;
use Yajra\Datatables\Datatables;
use App\Helpers\DtButtonHelper;
use App\Enums\DtButtonType;
use Auth;

class ItemCategoryService {

    public function get($id) {
        return ItemCategory::findOrFail($id);
    }

    public function lists() {
        return ItemCategory::orderBy('name')->select('name', 'id')->pluck('name', 'id');
    }

    public function store($input) {
        return ItemCategory::create($input->except('_token'));
    }

    public function update($input) {
      return ItemCategory::findOrFail($input['id'])->update($input->except(['id']));
    }

    public function destroy($id) {
        return ItemCategory::findOrFail($id)->delete();
    }

    public function datatables($input) {
        $query = ItemCategory::select();
        $datatables = Datatables::of($query)
                ->addColumn('actions', function($item_category) {
                    $actions = '';
                    if(Auth::user()->hasPermissionTo('update item_category'))
                        $actions .= DtButtonHelper::getByType(route('item_category.edit', ['item_category' => $item_category->id]), DtButtonType::EDIT);
                    if(Auth::user()->hasPermissionTo('delete item_category'))
                        $actions .= DtButtonHelper::getByType(route('item_category.destroy', ['item_category' => $item_category->id]), DtButtonType::DELETE);
                    return $actions;
                })->rawColumns(['actions']);
        return $datatables->make(true);
    }

}
