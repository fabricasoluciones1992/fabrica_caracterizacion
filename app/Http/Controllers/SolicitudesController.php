<?php
 
namespace App\Http\Controllers;
 
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
 
class SolicitudesController extends Controller
{
//  agregar reasons id y corregir esta vista
    public function index($proj_id,$use_id)
    {
       
        $solicitudes = DB::select("SELECT * FROM ViewSolicitudes");
        Controller::NewRegisterTrigger("A search was performed in the solicitudes table", 4,  $proj_id, $use_id);
 
        return response()->json([
           'status' => true,
            'data' => $solicitudes
        ], 200);
 
   
}
    public function store($proj_id,$use_id,Request $request)
    {
       
        if ($request->acc_administrator == 1) {
            $rules = [
                'sol_date' =>'date',
                'sol_name'=>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                'rea_id' =>'required|integer|max:1',
                'sol_typ_id' =>'required|integer|max:1',
                'stu_id' =>'required|integer|max:1',
 
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
                   Controller::NewRegisterTrigger("An insertion was made in the solicitudes table", 3,  $proj_id, $use_id);
       
                   return response()->json([
                    'status' => True,
                    'message' => "The request has been created successfully."
                   ], 200);
            }
        } else {
            return response()->json([
 
                'status' => false,
                'message' => 'Access denied. This action can only be performed by active administrators.'
            ], 403);
        }
   
}
    public function show($proj_id,$use_id,$id)
    {
       
        $solicitudes =  DB::select("SELECT * FROM ViewSolicitudes WHERE sol.sol_id = $id");

        if ($solicitudes == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched request was not found']
            ], 400);
        } else {
            Controller::NewRegisterTrigger("A search was performed in the solicitudes table", 4,  $proj_id, $use_id);
 
            return response()->json([
               'status' => true,
                'data' => $solicitudes
            ]);
        }
 
   
}
    public function update($proj_id,$use_id,Request $request, $id)
    {
       
        $solicitudes = Solicitud::find($id);
 
        if ($request->acc_administrator == 1) {
            $solicitudes = Solicitud::find($id);
            if ($solicitudes == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The searched request was not found']
                ], 400);
            } else {
                $rules = [
                    'sol_date' =>'date',
                    'sol_name'=>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                    'rea_id' =>'required|integer|max:1',
                    'sol_typ_id' =>'required|integer',
                    'stu_id' =>'required|integer'
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
                    $solicitudes->save();
                Controller::NewRegisterTrigger("An update was made in the solicitudes table", 1, $proj_id, $use_id);
 
                    return response()->json([
                    'status' => True,
                    'message' => "The request has been updated successfully."
                    ], 200);
                }
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. This action can only be performed by active administrators.'
            ], 403);
        }
   
}
    public function destroy($use_id,Solicitud $solicitudes)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available"
        ], 400);
    }
}