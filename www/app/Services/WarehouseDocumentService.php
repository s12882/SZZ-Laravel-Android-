<?php

namespace App\Services;

use App\Models\Item;
use App\Models\WarehouseDocument;
use Yajra\Datatables\Datatables;
use App\Helpers\DtButtonHelper;
use App\Enums\DtButtonType;
use App\Enums\WarehouseDocumentCategory;

class WarehouseDocumentService {

    public function get($id) {
        return WarehouseDocument::findOrFail($id);
    }

    public function lists() {
        return WarehouseDocument::orderBy('name')->select('name', 'id')->pluck('name', 'id');
    }

    public function store($input) {
        $category = $input['warehouse_document_category'];
        $input['created_by'] = \Auth::id();
        $document = WarehouseDocument::create($input->except('_token'));
        $items = array_values(json_decode($input['items'], true));
        foreach($items as $item){
            if($category == WarehouseDocumentCategory::Pobranie 
                || $category == WarehouseDocumentCategory::Likwidacja 
                || $category == WarehouseDocumentCategory::Zwrot)
                Item::findOrFail($item['item_id'])->decrement('amount', $item['amount']);
            else
                Item::findOrFail($item['item_id'])->increment('amount', $item['amount']);            
            
            $document->items()->attach($item['item_id'], ['amount' => $item['amount']]);
        }
        return $document;
    }

    public function destroy($id) {
        return WarehouseDocument::findOrFail($id)->delete();
    }

    public function datatables($input) {
        $query = WarehouseDocument::select()->with('author:id,first_name,surname');
        $datatables = Datatables::of($query)
                ->addColumn('actions', function($document) {
                    $actions = '';
                    if(\Auth::user()->hasPermissionTo('delete warehouse_document'))
                        $actions .= DtButtonHelper::getByType(route('warehouse_document.destroy', ['client' => $document->id]), DtButtonType::DELETE);
                    return $actions;
                })->addColumn('created_by',function($document){
                    return $document->author->first_name." ". $document->author->surname;
                })->addColumn('warehouse_document_category', function($document){
                    return WarehouseDocumentCategory::getKey($document->warehouse_document_category);
                });
        
        if ($input['warehouse_document_category'])
            $datatables->where('warehouse_document_category', $input['warehouse_document_category']);

        if ($input['date_from']) 
            $datatables->where('created_at', '>=', $input['date_from'].' 23:59:59');
        
        if ($input['date_to']) 
            $datatables->where('created_at', '<=', $input['date_to'].' 23:59:59');

        return $datatables->rawColumns(['actions'])->make(true);
    }

}
