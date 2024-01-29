<?php

namespace App\Http\Controllers;

use App\Models\Reason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReasonsController extends Controller
{
    public function index()
    {
        $reason = Reason::all();
        return response()->json([
            'status' => true,
            'data' => $reason
        ],200);
    }
    public function store(Request $request)
    {
        $rules = [
            'rea_name' => 'required|string|min:1|max:100',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
 
             'status' => False,
             'message' => $validator->errors()->all()
            ]);
        } else {
            $reason = new Reason($request->input());
            $reason->save();
            return response()->json([
             'status' => True,
             'message' => "El tipo de razon '".$reason->rea_name."' ha sido creado exitosamente."
            ],200);
        }  
    }
    public function show($id)
    {
        $reason = Reason::find($id);
        if ($reason == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se ha encontrado la razon solicitada']
            ],400);
        } else {
            return response()->json([
                'status' => true,
                'data' => $reason
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $reason = Reason::find($id);
        if ($reason == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se ha encontrado la razon solicitada']
            ],400);
        } else {
            $rules = [
              'rea_name' =>'required|string|min:1|max:100',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
              'status' => False,
              'message' => $validator->errors()->all()
                ]);
            } else {
                $reason->rea_name = $request->rea_name;
                $reason->save();
                return response()->json([
                    'status' => True,
                    'data' => "la razon ".$reason->rea_name." ha sido actualizado exitosamente."
                ],200);
            };
        }
    }
    public function destroy(string $id)
    {
        return response()->json([
          'status' => false,
          'message' => "Funcion no disponible"
        ],400);
    }
}
