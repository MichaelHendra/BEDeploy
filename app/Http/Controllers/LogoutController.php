<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        try{
            if(! $token = JWTAuth::getToken()){
                return response()->json([
                    'success' => false,
                    'message' => 'Token not Provided'
                ], 400);            
            }
            $removeToken = JWTAuth::invalidate($token);

            return response()->json([
                'success' => true,
                'message' => 'Logout Berhasil'
            ]);
        }catch(JWTException $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout, please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
