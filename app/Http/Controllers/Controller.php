<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use App\Exceptions\ApiException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function ApiResponceSuccess(string|array|object $data, int $code = 200):JsonResponse
    {
        if($code < 200 || $code  > 299)
        {

            throw new ApiException("success response code not valid");

        }


        if(empty($data)){
 
              throw new ApiException("success response data is empty");  
    
        }else{

            if(is_string($data) || is_array($data)){

              return response()->json(['data' => $data], $code);

            }else if(is_object($data)){

                $adata = get_object_vars($data);
                
                if(empty($adata)){

                    throw new ApiException("success response data object is empty");

                }else{

                    $oadata = [];
                        
                    foreach($adata as $key => $val){
                        
                        $oadata[$key] = $val;

                    }

                    return response()->json(['data' => $data], $code);

                }

            }

             
        }
      
    }

    protected function ApiResponceError(string $message, int $code = 200):JsonResponse
    {
        if($code < 300)
        {

            throw new ApiException("error response code not valid");

        }

        if(empty($message)){

            throw new ApiException("error response message is empty");
    
        }else{

            return response()->json(['error' => $message], $code);

        }
    }


    
}
