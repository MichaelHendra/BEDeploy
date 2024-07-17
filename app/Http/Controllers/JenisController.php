<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApihResource;
use App\Models\Jenis_Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisController extends Controller
{
    public function index()  {
        $data = Jenis_Movie::all();
        return response()->json($data);
    }
    public function store(Request $request)  {
        $validator = Validator::make($request->all(),[
            'jenis' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $data = Jenis_Movie::create([
            'jenis' => $request->jenis
        ]);

        return response()->json($data);
    }

    public function update(Request $request, $id)  {
        $validator = Validator::make($request->all(),[
            'jenis' => 'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $data = Jenis_Movie::find($id);
        $data -> update([
            'jenis' => $request->jenis
        ]);
        return new ApihResource(true, 'Data Berhasil Diubah', $data);
    }

    public function delete($id) {
        $data = Jenis_Movie::find($id);
        $data->delete();
        return new ApihResource(true, 'Data Berhasil Dihapus', null);
    }

}
