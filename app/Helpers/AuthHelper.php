<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Passport\Passport;
class AuthHelper{


public static function loginRequestValidate($request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:6',
    ]);

        return $validator;
}

public static function setBearerAccessTokenExpiration(?int $remember):void
{

if($remember == '1'){

    $BearerToken_exp = 60;

}else{

    $BearerToken_exp = 3;

}

 Passport::personalAccessTokensExpireIn(now()->addMinutes($BearerToken_exp));

}

public static function registerRequestValidate($request){

    $validator = Validator::make($request->all(), [
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:6',
    ]);

    return $validator;

}

}