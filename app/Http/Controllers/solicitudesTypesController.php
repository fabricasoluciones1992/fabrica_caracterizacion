<?php

namespace App\Http\Controllers;

use App\Models\SolicitudType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SolicitudesTypesController extends Controller
{
    public function index($proj_id)
    {
        $token = Controller::auth();

        $solicitudTypes = SolicitudType::all();
        Controller::NewRegisterTrigger("A search was performed in the solicitudes types table", 4, $proj_id, $token['use_id']);
        return response()->json([
            'status' => true,
            'data' => $solicitudTypes
        ], 200);
    }

    public function store($proj_id,Request $request)
    {
        $token = Controller::auth();

        
        session_start();
        if ($_SESSION['acc_administrator'] == 1) {
            $rules = [
                'sol_typ_name' => 'required|string|min:1|max:100|regex:/^[A-Z\s]+$/',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $solicitudTypes = new SolicitudType($request->input());
                $solicitudTypes->save();
                Controller::NewRegisterTrigger("An insertion was made in the solicitudes types table", 3,  $proj_id, $token['use_id']);
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

    public function show($proj_id,$id)
    {
        $token = Controller::auth();

        $solicitudTypes = SolicitudType::find($id);
        if ($solicitudTypes == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested reason was not found']
            ], 400);
        } else {
            Controller::NewRegisterTrigger("A search was performed in the solicitudes types table", 4,  $proj_id, $token['use_id']);
            return response()->json([
                'status' => true,
                'data' => $solicitudTypes
            ]);
        }
    }

    public function update($proj_id,Request $request, $id)
    {
        $token = Controller::auth();

        
        session_start();
        if ($_SESSION['acc_administrator'] == 1) {
            $solicitudTypes = SolicitudType::find($id);
            if ($solicitudTypes == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested reason was not found']
                ], 400);
            } else {

                $rules = [
                    'sol_typ_name' => 'required|string|min:1|max:100|regex:/^[A-Z\s]+$/',
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
                    Controller::NewRegisterTrigger("An update was made in the solicitudes types table", 1,  $proj_id, $token['use_id']);
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

    public function destroy(SolicitudType $solicitudTypes)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available"
        ], 400);
    }
}
