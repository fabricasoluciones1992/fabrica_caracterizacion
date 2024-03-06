<?php

namespace App\Http\Controllers;

use App\Models\SolicitudeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SolicitudesTypesController extends Controller
{
    public function index($proj_id,$use_id)
    {        
            $solicitudTypes = SolicitudeType::all();
            Controller::NewRegisterTrigger("A search was performed in the solicitudes types table", 4, $proj_id, $use_id);
            return response()->json([
                'status' => true,
                'data' => $solicitudTypes
            ], 200);
        
    }

    public function store($proj_id,$use_id,Request $request)
    {
        
            if ($request->acc_administrator == 1) {
                $rules = [
                    'sol_typ_name' => 'required|string|min:1|max:100|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    $solicitudTypes = new SolicitudeType($request->input());
                    $solicitudTypes->sol_typ_status=1;
                    $solicitudTypes->save();
                    Controller::NewRegisterTrigger("An insertion was made in the solicitudes types table", 3,  $proj_id, $use_id);
                    return response()->json([
                        'status' => true,
                        'message' => "The reason type '".$solicitudTypes->sol_typ_name."' has been created successfully."
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
        
            $solicitudTypes = SolicitudeType::find($id);
            if ($solicitudTypes == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested solicitudes types was not found']
                ], 400);
            } else {
                Controller::NewRegisterTrigger("A search was performed in the solicitudes types table", 4, $proj_id, $use_id);
                return response()->json([
                    'status' => true,
                    'data' => $solicitudTypes
                ]);
            }
        
    }

    public function update($proj_id,$use_id,Request $request, $id)
    {
        
            
            if ($request->acc_administrator == 1) {
                $solicitudTypes = SolicitudeType::find($id);
                if ($solicitudTypes == null) {
                    return response()->json([
                        'status' => false,
                        'data' => ['message' => 'The requested solicitudes types was not found']
                    ], 400);
                } else {

                    $rules = [
                        'sol_typ_name' => 'required|string|min:1|max:100|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                    ];
                    $validator = Validator::make($request->input(), $rules);
                    if ($validator->fails()) {
                        return response()->json([
                            'status' => false,
                            'message' => $validator->errors()->all()
                        ]);
                    } else {
                        $solicitudTypes->sol_typ_name = $request->sol_typ_name;
                        $solicitudTypes->save();
                        Controller::NewRegisterTrigger("An update was made in the solicitudes types table", 1,  $proj_id, $use_id);
                        return response()->json([
                            'status' => true,
                            'message' => "The reason '".$solicitudTypes->sol_typ_name."' has been updated successfully."
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

    public function destroy($proj_id,$use_id, $id)
    {

        $solicitudTypes = SolicitudeType::find($id);
        
        
            if ($solicitudTypes->sol_typ_status == 1){
                $solicitudTypes->sol_typ_status = 0;
                $solicitudTypes->save();
                Controller::NewRegisterTrigger("An delete was made in the solicitudes types table",1,$proj_id, $use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested solicitudes types has been disabled successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The requested solicitudes types has already been disabled previously'
                ]);
            }  
        
    }
}
