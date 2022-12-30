<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use GoogleAuthHelper;


class GoogleAuthUrlController extends Controller
{
    
    public function getGoogleAuthUrl(Request $request):JsonResponse
    {
        $GoogleAuthUrl = GoogleAuthHelper::getGoogleAuthUrl($request['redirect_url']);

        if(!empty($GoogleAuthUrl['error'])){
    
            return response()->json(['error' => $GoogleAuthUrl['error']], $GoogleAuthUrl['status']);
       
        }else{

            return response()->json(['data' => $GoogleAuthUrl], 200);
        }

    }

}
