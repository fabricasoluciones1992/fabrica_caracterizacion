<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultationController extends Controller
{
    public function index($proj_id,$use_id)
    {
        $consultation = Consultation::all();
        Controller::NewRegisterTrigger("A search was performed on the reasons table",4,$proj_id, $use_id);
        return response()->json([
            'status' => true,
            'data' => $consultation
        ],200);
    }
    public function store($proj_id,$use_id,Request $request)
    {
        if ($request->acc_administrator == 1) {
            $rules = [
                'cons_date' => 'date',
                'cons_reason' => 'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                'cons_description' => 'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                'cons_weight' => 'required|integer',
                'cons_height' => 'required|integer',
                'cons_imc' => 'required|integer',
                'cons_vaccination' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            }else{
                $currentDate = now()->toDateString();
                $request->merge(['cons_date' => $currentDate]);

                $consultation = new Consultation($request->input());
                $consultation->save();
                Controller::NewRegisterTrigger("An insertion was made in the consultations table",3,$proj_id, $use_id);
    
                return response()->json([
                    'status' => True,
                    'message' => "The consultations has been created successfully."
                ],200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. This reason can only be performed by active administrators.'
            ], 403); 
        }
    }
    public function show($proj_id,$use_id,$id)
    {
        $consultation = Consultation::find($id);

        if ($consultation == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched consultations was not found']
            ], 400);
        } else {
            Controller::NewRegisterTrigger("A search was performed in the consultations table", 4,  $proj_id, $use_id);
            return response()->json([
                'status' => true,
                'data' => $consultation
            ]);
        }
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        if ($request->acc_administrator == 1) {
            $consultation = Consultation::find($id);
            if ($consultation == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The searched request was not found']
                ], 400);
            } else {
                $rules = [
                    'cons_date' => 'date',
                    'cons_reason' => 'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                    'cons_description' => 'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                    'cons_weight' => 'required|integer',
                    'cons_height' => 'required|integer',
                    'cons_imc' => 'required|integer',
                    'cons_vaccination' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {
                    $currentDate = now()->toDateString();
                    $request->merge(['cons_date' => $currentDate]);
 
                    $consultation->cons_date = $request->cons_date;
                    $consultation->cons_reason = $request->cons_reason;
                    $consultation->cons_description = $request->cons_description;
                    $consultation->cons_weight = $request->cons_weight;
                    $consultation->cons_height = $request->cons_height;
                    $consultation->cons_imc = $request->cons_imc;
                    $consultation->cons_vaccination = $request->cons_vaccination;
                    $consultation->save();
                    
                    Controller::NewRegisterTrigger("An update was made in the consultations table", 1, $proj_id, $use_id);
                    return response()->json([
                    'status' => True,
                    'message' => "The consultations has been updated successfully."
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
        return response()->json([
            'status' => false,
            'message' => 'no existe la opcion de eliminar'
        ]);
    }
}
