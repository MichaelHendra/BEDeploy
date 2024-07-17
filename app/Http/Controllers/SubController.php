<?php

namespace App\Http\Controllers;

use App\Models\Subs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubController extends Controller
{
    public function index() {
        $data = Subs::all();
        return response()->json($data);
    }

    public function store(Request $request)  {
        $validator = Validator::make($request->all(),[
            'sub_day' => 'required',
            'nama_sub' => 'required',
            'harga' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $data = Subs::create([
            'sub_day' => $request->sub_day,
            'nama_sub' => $request->nama_sub,
            'harga' => $request->harga
        ]);

        return response()->json($data);
    }

    public function update(Request $request, $id)  {
        $validator = Validator::make($request->all(),[
            'sub_day' => 'required',
            'nama_sub' => 'required',
            'harga' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $data = Subs::find($id);

        $data->update([
            'sub_day' => $request->sub_day,
            'nama_sub' => $request->nama_sub,
            'harga' => $request->harga
        ]);

        return response()->json($data);
    }

    public function destroy($id)  {
        $data = Subs::find($id);

        $data->delete();

        return response()->json("Data Berhasil Dihapus");
    }
}
