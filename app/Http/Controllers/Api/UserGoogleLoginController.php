<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\Interfaces\AuthInterface;
use AuthHelper;
use GoogleAuthHelper;
use Carbon\Carbon;


class UserGoogleLoginController extends Controller implements AuthInterface
{
   
    public function login($request):JsonResponse
    {
        
        
        // exchange Google Auth Code to Google access token object
        $GoogleAccessToken = GoogleAuthHelper::GoogleAuthCodeToAccesToken($request["auth_code"]);
   
        if(!is_object($GoogleAccessToken) && !empty($GoogleAccessToken['error'])){

           return Controller::ApiResponceError($GoogleAccessToken['error'], $GoogleAccessToken['status']);

        }
        
        $service = new \Google\Service\Oauth2($GoogleAccessToken);
       
        // get Google user profile data by Google access token
        $userFromGoogle = $service->userinfo->get();

        if(empty($userFromGoogle)){

            return Controller::ApiResponceError('user from Google unavailable', 503);

        }

        $userFromGoogle->AccessToken = $GoogleAccessToken;
        
        $userFromGoogleUpdate = GoogleAuthHelper::UserFromGoogleUpdateOrRegister($userFromGoogle);


        if (!$userFromGoogleUpdate) {
        
            return Controller::ApiResponceError('user Google login error', 500);

        }

        AuthHelper::setBearerAccessTokenExpiration($request['remember']);

        $Token = $userFromGoogleUpdate->createToken("Google");

        $BearerToken = $Token->accessToken;

        $BearerTokenExp = $Token->token->expires_at->diffInMinutes(Carbon::now());

        if($userFromGoogleUpdate->event == 'login')
        {

            $statusCode = 200;  


        }else if($userFromGoogleUpdate->event == 'register') {

            $statusCode = 201;

        }
        
        $output_data = array(
            'bearerToken' => $BearerToken, 
            'provider' => 'Google', 
            'bearerTokenExp' => $BearerTokenExp
        );

        return Controller::ApiResponceSuccess($output_data, $statusCode);

    }  

}
