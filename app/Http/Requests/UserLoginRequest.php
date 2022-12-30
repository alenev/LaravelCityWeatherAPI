<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
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
       
        return [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ];

    }

    // public function moreValidation($validator){

    //     $validator->after(function($validator)
    //     {
    //         foreach ($this->input as $n=>$v) {
    //             if ($v == 'password' && $n[$v] == '123458') {
    //                 $validator->errors()->add('error', 'validator error text');
    //                 break;
    //             }
    //         }
    //     });
    // }

}
