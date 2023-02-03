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

    $barerTokenExp = 60;

}else{

    $barerTokenExp = 3;

}

 Passport::personalAccessTokensExpireIn(now()->addMinutes($barerTokenExp));

}

public static function registerRequestValidate($request){

    $validator = Validator::make($request->all(), [
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:6',
    ]);

    return $validator;

}

}