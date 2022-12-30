<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\AuthHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class UserRegisterController extends Controller
{

    public function register($request):JsonResponse{

     // validating request data
    $validator = AuthHelper::registerRequestValidate($request);   

    if($validator->fails()){

      return response()->json(['error'=>$validator->errors()->all()], 422);

    }


    $request['password'] = Hash::make($request['password']);

    $request['provider_name'] = 'login api';    

    $user = User::create($request->toArray());

    if(!$user){

        return response()->json(['error' => 'user login form registering problem'], 500);

    }

    // send verify email to registered user
    $user->sendEmailVerificationNotification();

    return response()->json(['error' => 'user login form register. Email is not verified. Email sended to '.$user->email], 202);
}

}
