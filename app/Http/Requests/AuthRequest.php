<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthRequest extends FormRequest
{
	public $validator = null;
	
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
        $auth_code = $this->auth_code;

        return []+(!empty($auth_code) ? $this->GoogleLogin() : $this->FormLogin());
    }

    protected function GoogleLogin() 
    {
        return [
            'auth_code' => 'string|min:5'
        ];
    }

    protected function FormLogin()
    {
        
      $user = DB::table('users')->where('email', $this->email)->first();

        if(!empty($user)){

          $vs = 'required|email|unique:users,email,'.$user->id;

        }else{

          $vs = 'required|email|unique:users';

        }

        return [
            'email' => $vs,
            'password' => 'required|string|min:6',
           // 'remember' => 'string' // debug
        ];
    }
	
	
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->validator = $validator;
    }

}
