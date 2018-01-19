<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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

        $user = User::find($this->user);

        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'login' => 'required|max:255|unique:users,login',
                        'first_name' => 'required|max:255',
                        'surname' => 'required|max:255',
                        'email' => 'required|email|unique:users,email',
                        'phoneNumber' => 'required|numeric|digits_between:9,14|unique:users,phoneNumber',
                        'section_id' => 'required'
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    if(preg_match('/profile/', \URL::previous()))
                        return [
                            'email' => 'required|email|unique:users,email,'.$user->id,
                            'phoneNumber' => 'required|numeric|digits_between:9,14|unique:users,phoneNumber,'.$user->id,
                        ];
                    else
                        return [
                            'login' => 'required|max:255|unique:users,login,'.$user->id,
                            'first_name' => 'required|max:255',
                            'surname' => 'required|max:255',
                            'email' => 'required|email|unique:users,email,'.$user->id,
                            'phoneNumber' => 'required|numeric|digits_between:9,14|unique:users,phoneNumber,'.$user->id
                        ];
                }
            default:break;
        }
    }
}
