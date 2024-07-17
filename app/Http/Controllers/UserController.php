<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        $data = User::join('sub_plans', 'sub_plans.sub_id', 'users.plan_id')
            ->where('users.id', $id)
            ->get();
        return response()->json($data);
    }
}
