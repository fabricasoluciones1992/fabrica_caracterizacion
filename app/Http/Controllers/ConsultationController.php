<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class ConsultationController extends Controller
{
    public function index()//tipo de persona id tipo de documento
    {
        $consultations = DB::select("SELECT consultations.*, persons.* FROM consultations INNER JOIN persons ON consultations.per_id = persons.per_id");
        return response()->json([
            'status' => true,
            'data' => $consultations
        ],200);
    }
    public function store(Request $request)
    {
        if ($request->acc_administrator == 1) {
            $rules = [
                    'stu_enr_semester' =>'required|numeric|max:7|min:1',
                    'stu_id' =>'required|exists:students',
                    'peri_id'=>'required|exists:periods',
                    "use_id" =>'required|exists:users',
                'cons_reason' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ñÑÁÉÍÓÚÜáéíóúü\s]+$/',
                'cons_description' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ñÑÁÉÍÓÚÜáéíóúü\s]+$/',
                'per_id' => 'required|integer'
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
                $consultation->cons_date = date('Y-m-d');
                $consultation->save();
                Controller::NewRegisterTrigger("An insertion was made in the consultations table'$consultation->id'",3,$request->use_id);
                
                return response()->json([
                    'status' => True,
                    'message' => "The consultations has been created successfully.",
                ],200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. This reason can only be performed by active administrators.'
            ], 403); 
        }
    }

    public function show($id)
    {
        $consultation = Consultation::search($id);

        if ($consultation == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched consultations was not found']
            ], 400);
        } else {

            return response()->json([
                'status' => true,
                'data' => $consultation
            ]);
        }
    }
    public function update(Request $request, $id)
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
                    'stu_enr_semester' =>'required|numeric|max:7|min:1',
                    'stu_id' =>'required|exists:students',
                    'peri_id'=>'required|exists:periods',
                    "use_id" =>'required|exists:users',
                    'cons_date' => 'date',
                    'cons_reason' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ÁÉÍÓÚÜáéíóúü\s]+$/',
                    'cons_description' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ÁÉÍÓÚÜáéíóúü\s]+$/',
                    
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
                    
                    $consultation->save();
                    
                    Controller::NewRegisterTrigger("An update was made in the consultations table", 4, $request->use_id);
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
    public function destroy(Request $request,$id)
    {
        return response()->json([
            'status' => false,
            'message' => 'function not available.'
        ]);
    }
}
