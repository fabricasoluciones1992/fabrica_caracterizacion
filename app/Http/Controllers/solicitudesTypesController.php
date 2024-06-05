<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\SolicitudeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SolicitudesTypesController extends Controller
{
    public function index()
    {        
            $solicitudesTypes = SolicitudeType::all();
            return response()->json([
                'status' => true,
                'data' => $solicitudesTypes
            ], 200);
        
    }

    public function store(Request $request)
    {
        
            if ($request->acc_administrator == 1) {
                $rules = [

                    'sol_typ_name' => 'required|string|min:1|max:100|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u|unique:solicitude_types'
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
                    Controller::NewRegisterTrigger("An insertion was made in the solicitudes types table'$solicitudTypes->sol_typ_id'", 3, $request->use_id);
                    // $id = $solicitudTypes->sol_typ_id;
                    // $bienestar_news=SolicitudesTypesController::Getbienestar_news($id);
                    return response()->json([
                        'status' => true,
                        'message' => "The solicitud type '".$solicitudTypes->sol_typ_name."' has been created successfully.",

                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This action can only be performed by active administrators.'
                ], 403); 
            }
        
    }


    public function show($id)
    {
        
            $solicitudTypes = SolicitudeType::find($id);

            if ($solicitudTypes == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested solicitudes types was not found']
                ], 400);
            } else {
                
                return response()->json([
                    'status' => true,
                    'data' => $solicitudTypes
                ]);
            }
        
    }

    public function update(Request $request, $id)
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
                    $validate = Controller::validate_exists($request->sol_typ_name, 'solicitude_types', 'sol_typ_name', 'sol_typ_id', $id);

                    if ($validator->fails()||$validate==0) {
                        return response()->json([
                            'status' => false,
                            'message' => $validator->errors()->all()
                        ]);
                    } else {
                        $solicitudTypes->sol_typ_name = $request->sol_typ_name;
                        $solicitudTypes->save();
                        Controller::NewRegisterTrigger("An update was made in the solicitudes types table", 4,$request->use_id);
                        return response()->json([
                            'status' => true,
                            'message' => "The solicitud '".$solicitudTypes->sol_typ_name."' has been updated successfully."
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

    public function destroy(Request $request,$id)
    {

        $solicitudTypes = SolicitudeType::find($id);
        
        $newST=($solicitudTypes->sol_typ_status ==1)?0:1;
                $solicitudTypes->sol_typ_status = $newST;
                $solicitudTypes->save();
                Controller::NewRegisterTrigger("An change status was made in the solicitudes types table",2,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested solicitudes types has been change status successfully'
                ]);
            
        
    }
}
