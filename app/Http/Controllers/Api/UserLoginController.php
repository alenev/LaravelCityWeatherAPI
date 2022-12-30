<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\Interfaces\AuthInterface;
use App\Helpers\AuthHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class UserLoginController extends Controller implements AuthInterface
{
    
    public function login($request):JsonResponse
    {

        $user = User::where('email', $request["email"])->first();

       // register not exist user
        if (!$user) { 
            
            $UserRegisterController = new UserRegisterController();

            $register = $UserRegisterController->register($request);

            return $register;

        //login exist user
        }else{

        if(empty($user->email_verified_at) &&  $user->provider_name !== 'google' && !$request['noverify_email']){

            $user->sendEmailVerificationNotification();

            return response()->json(['error' => 'User email is not verified. Email sended to '. $user->email], 422);
           
        }else if( $user->provider_name == 'google'){
              
            return  response()->json(["error" => "User register via Google"], 422);
        
        }else{

            $password_check = Hash::check($request['password'],  $user->password);
            
            if(!$password_check) {

              return  response()->json(["errors" => "Password mismatch"], 422);
           
            }

             AuthHelper::setBearerAccessTokenExpiration($request['remember']);

             $Token =  $user->createToken('Login form');
            
             $BearerToken = $Token->accessToken;
             
             $BearerTokenExp = $Token->token->expires_at->diffInMinutes(Carbon::now());

            return response()->json(['data' => $BearerToken, 'provider' => "Login form", 'bearerTokenExp' => $BearerTokenExp], 200);
            
        }      
    }

    }
    
}

