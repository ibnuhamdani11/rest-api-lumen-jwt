<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
  
class Controller extends BaseController
{
    protected $pathDocument   = "assets/";
    public function __construct()
    {
        $this->pathDocument = config('assets.upload');
    }

    public function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null
        ], 200);
    }
}