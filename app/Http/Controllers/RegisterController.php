<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8',
            'telp' => 'required|min:11',
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'telp' => $request->telp,
            'image' => 0,
            'order_id' => 0,
            'plan_id' => 1,
            'date_sub' => '1999-01-01',
            'valid_date' => '1999-01-01'
        ]);

        if($user){
            return response()->json([
                'success' => true,
                'user' => $user
            ], 201);
        }

        return response() -> json([
            'success' => false,
        ], 409);
    }
}
