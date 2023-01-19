<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
     public function auth(AuthRequest $request):JsonResponse
     {
       if (isset($request->validator) && $request->validator->fails()) { 
 
          $validation_errors = $request->validator->errors()->messages();
		  
          $validation_errors_first = current((array)$validation_errors);
		  
          return Controller::ApiResponceError($validation_errors_first[0], 404); 

       }else if(!empty($request['auth_code'])){
            
            // login or register user via Google 
            $auth = new UserGoogleLoginController();

        }else if(!empty($request['email']) && !empty($request['password'])){

            // login or register user via email and password
            $auth = new UserLoginController();

        }
        
        $login = $auth->login($request);
 
        return $login;

     }  

}




