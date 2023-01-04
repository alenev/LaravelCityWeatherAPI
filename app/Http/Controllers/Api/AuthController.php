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
       
        if(!empty($request['auth_code'])){
            
            // login or register user via Google 
            $auth = new UserGoogleLoginController();

        }else if(!empty($request['email']) && !empty($request['password'])){

            // login or register user via email and password
            $auth = new UserLoginController();

        }else{

            return Controller::ApiResponceError('input data validation error', 404);
        }
        
        $login = $auth->login($request);
 
        return $login;

     }  

}




