<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
        if($this->route()->getName() == "task.reserve")
            return ['task_id' => 'int|unique:task_user,task_id,NULL,NULL,user_id,'.\Auth::id()];
        else
            return [
                'name' => ['required', 'max:255'], 
                'description' => ['required', 'max:255'], 
                'location' => ['required', 'max:255'], 
                'section_id' => ['required']
            ];          
    }
}
