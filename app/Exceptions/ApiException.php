<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e):JsonResponse {
            
            return response()->json(['error' => $e], 500);
            
        });
    }
}
