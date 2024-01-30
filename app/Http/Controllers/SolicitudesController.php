<?php

namespace App\Http\Controllers;

use App\Models\solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SolicitudesController extends Controller
{
 
    public function index()
    {
        $solicitudes = DB::select("SELECT 
        solicitudes.sol_id,
        solicitudes.sol_date,
        solicitudes.sol_description,
        solicitude_types.sol_typ_name,
        factors.fac_name,
        persons.per_name
    FROM 
        solicitudes
    INNER JOIN 
        solicitude_types ON solicitudes.sol_typ_id = solicitude_types.sol_typ_id
    INNER JOIN 
        factors ON solicitudes.fac_id = factors.fac_id
    INNER JOIN 
        students ON solicitudes.stu_id = students.stu_id
    INNER JOIN 
        persons ON students.per_id = persons.per_id;
    
            ");
        return response()->json([
           'status' => true,
            'data' => $solicitudes
        ],200);
        Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla solicitudes",4,2,1);

    }
 
    public function store(Request $request)
    {
        // return $request;
        $rules = [
            'sol_date' =>'required|date',
            'sol_description' =>'required|string|min:1|max:50',
            'sol_typ_id' =>'required|integer',
            'stu_id' =>'required|integer',
            'fac_id' =>'required|integer'

        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
             'status' => False,
             'message' => $validator->errors()->all()
            ]);
        }else{
            $solicitudes = new solicitud($request->input());
            $solicitudes->save();
            return response()->json([
             'status' => True,
             'message' => "The request success has been created."
            ],200);
        }
        Controller::NewRegisterTrigger("Se realizo una insercion en la tabla solicitudes",3,2,1);

    }
    public function show($id)
    {
        $solicitudes =  DB::select("SELECT 
        solicitudes.sol_id,
        solicitudes.sol_date,
        solicitudes.sol_description,
        solicitude_types.sol_typ_name,
        factors.fac_name,
        persons.per_name
    FROM 
        solicitudes
    INNER JOIN 
        solicitude_types ON solicitudes.sol_typ_id = solicitude_types.sol_typ_id
    INNER JOIN 
        factors ON solicitudes.fac_id = factors.fac_id
    INNER JOIN 
        students ON solicitudes.stu_id = students.stu_id
    INNER JOIN 
        persons ON students.per_id = persons.per_id
    WHERE 
        solicitudes.sol_id = $id");
        if ($solicitudes == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched requests was not found']
            ],400);
        }else{
            return response()->json([
               'status' => true,
                'data' => $solicitudes
            ]);
        }
        Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla solicitudes",4,2,1);

    }
    public function update(Request $request,$id)
    {
        $solicitudes = solicitud::find($id);
        if ($solicitudes == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched novelty was not found']
            ],400);
        }else{
            $rules = [
                'sol_date' =>'required|date',
            'sol_description' =>'required|string|min:1|max:50',
            'sol_typ_id' =>'required|integer',
            'stu_id' =>'required|integer',
            'fac_id' =>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json()([
                   'status' => False,
                  'message' => $validator->errors()->all()
                ]);
            }else{
                $solicitudes->sol_date = $request->sol_date;
                $solicitudes->sol_description = $request->sol_description;
                $solicitudes->sol_typ_id = $request->sol_typ_id;
                $solicitudes->fac_id = $request->fac_id;
                $solicitudes->save();
                return response()->json([
                  'status' => True,
                  'message' => "The requests has been updated."
                ],200);
            }
        }
        Controller::NewRegisterTrigger("Se realizo una actualizacion en la tabla solicitudes",1,2,1);

    }
    public function destroy(solicitud $solicitudes)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
         ],400);
         Controller::NewRegisterTrigger("Se realizo una eliminacion en la tabla solicitudes",2,2,1);

    }
}
