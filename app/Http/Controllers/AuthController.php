<?php
namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index(Request $request)  {
        Transaksi::create([
            'order_id' => $request->order_id,
            'user_id' => $request->user_id,
            'plan_id' => $request->plan_id,
            'status' => $request->status
        ]);
    }
}
