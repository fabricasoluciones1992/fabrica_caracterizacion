<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
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
        Controller::NewRegisterTrigger("A search was performed in the solicitudes table", 4, 2, 1);

        return response()->json([
           'status' => true,
            'data' => $solicitudes
        ], 200);

    }
 
    public function store(Request $request)
    {
        // return $request;
        $rules = [
            'sol_date' =>'date',
            'sol_description' =>'required|string|min:1|max:250',
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
            $currentDate = now()->toDateString();

            $request->merge(['sol_date' => $currentDate]);
            $solicitudes = new Solicitud($request->input());
            $solicitudes->save();
            Controller::NewRegisterTrigger("An insertion was made in the solicitudes table", 3, 2, 1);

            return response()->json([
             'status' => True,
             'message' => "The request has been created successfully."
            ], 200);
        }

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
                "data" => ['message' => 'The searched request was not found']
            ], 400);
        } else {
            Controller::NewRegisterTrigger("A search was performed in the solicitudes table", 4, 2, 1);

            return response()->json([
               'status' => true,
                'data' => $solicitudes
            ]);
        }

    }
    public function update(Request $request, $id)
    {
        $solicitudes = Solicitud::find($id);
        if ($solicitudes == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched request was not found']
            ], 400);
        } else {
            $rules = [
                'sol_date' =>'date',
                'sol_description' =>'required|string|min:1|max:250',
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
            } else {
                $currentDate = now()->toDateString();

                $request->merge(['sol_date' => $currentDate]);

                $solicitudes->sol_date = $request->sol_date;
                $solicitudes->sol_description = $request->sol_description;
                $solicitudes->sol_typ_id = $request->sol_typ_id;
                $solicitudes->fac_id = $request->fac_id;
                $solicitudes->save();
                Controller::NewRegisterTrigger("An update was made in the solicitudes table", 1, 2, 1);

                return response()->json([
                  'status' => True,
                  'message' => "The request has been updated successfully."
                ], 200);
            }
        }

    }
    public function destroy(Solicitud $solicitudes)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available"
         ], 400);

    }
}
