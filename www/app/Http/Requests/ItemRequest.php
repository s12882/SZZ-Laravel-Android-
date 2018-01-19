<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        $item = Item::find($this->item);
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'name' => 'required|max:255',
                        'type' => 'required|max:255',
                        'amount' => 'required|numeric',
                        'ean' => 'required|digits_between:8,13|unique:items,ean',
                        'category_id' => 'required'
                        ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'name' => 'required|max:255',
                        'type' => 'required|max:255',
                        'amount' => 'required|numeric',
                        'ean' => 'required|digits_between:8,13|unique:items,ean,'.$item->id,
                        'category_id' => 'required'
                    ];
                }
            default:break;
        }
        
    }
}
