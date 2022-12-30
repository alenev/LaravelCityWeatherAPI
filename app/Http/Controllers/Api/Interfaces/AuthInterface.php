<?php

namespace App\Http\Controllers\Api\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface AuthInterface {

    public function login($request):JsonResponse;

}