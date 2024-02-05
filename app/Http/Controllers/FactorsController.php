<?php

namespace App\Http\Controllers;

use App\Models\factor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class FactorsController extends Controller
{
    public function index()
    {
        $token = Controller::auth();
        $data = Controller::genders($token);
        $factors = factor::all();
        Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla factors",4,2,$token['use_id']);

        return response()->json([
            'status' => true,
            'data' => $factors
        ],200);

    }
    public function store(Request $request)
    {
        $rules = [
            'fac_name' => 'required|string|min:1|max:50'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
 
             'status' => False,
             'message' => $validator->errors()->all()
            ]);
        }else{
            $factor = new factor($request->input());
            $factor->save();
            Controller::NewRegisterTrigger("Se realizo una insercion en la tabla factors",3,2,1);

            return response()->json([
             'status' => True,
             'message' => "El tipo de factor ".$factor->fac_name." ha sido creado exitosamente."
            ],200);
        }

    }
    public function show($id)
    {
        $factor = factor::find($id);
        if ($factor == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el factor solicitado']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla factors",4,2,1);

            return response()->json([
                'status' => true,
 
                'data' => $factor
            ]);
        }

    }
    public function update(Request $request, $id)
    {
        $factor = factor::find($id);
        if ($factor == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el factor solicitado']
            ],400);
        }else{
            $rules = [
                'fac_name' => 'required|string|min:1|max:50'
 
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
               'status' => False,
               'message' => $validator->errors()->all()
                ]);
            }else{
                $factor->fac_name = $request->fac_name;
                $factor->save();
                Controller::NewRegisterTrigger("Se realizo una actualizacion en la tabla factors",1,2,1);

                return response()->json([
             'status' => True,
                   'data' => "el factor ".$factor->fac_name." ha sido actualizado exitosamente."
                ],200);
            };
        }

    }
    public function destroy(factor $factors)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
 
        ],400);

    }
    
}
