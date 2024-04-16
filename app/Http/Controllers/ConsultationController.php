<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class ConsultationController extends Controller
{
    public function index()
    {
        $consultations = Consultation::all();
        return response()->json([
            'status' => true,
            'data' => $consultations
        ],200);
    }
    public function store(Request $request)
    {
        if ($request->acc_administrator == 1) {
            $rules = [
                'cons_date' => 'date',
                'cons_reason' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ñÑÁÉÍÓÚÜáéíóúü\s]+$/',
                'cons_description' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ñÑÁÉÍÓÚÜáéíóúü\s]+$/',
                'cons_weight' => 'required|integer',
                'cons_height' => 'required|integer',
                'cons_imc' => 'required|integer',
                'cons_vaccination' => 'required|string|min:1|max:50|regex:/^[a-zA-Z0-9nÑÁÉÍÓÚÜáéíóúü\s]+$/',
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
                Controller::NewRegisterTrigger("An insertion was made in the consultations table'$consultation->id'",3,$request->use_id);
                // $id = $consultation->id;
                // $bienestar_news=ConsultationController::Getbienestar_news($id);
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
//     public function Getbienestar_news($id)
// {
//     $cons_id = $id;
//     $bienestar_news = DB::table('bienestar_news')
//         ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//         ->select('bie_new_date', 'persons.per_name')
//         ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the consultations table\'$cons_id\''")
//         ->get();

//     if ($bienestar_news->count() > 0) {
//         return $bienestar_news[0];
//     } else {
//         return null;
//     }
// }
    public function show($id)
    {
        $consultation = Consultation::find($id);
        // $bienestar_news=ConsultationController::Getbienestar_news($id);

        if ($consultation == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched consultations was not found']
            ], 400);
        } else {
            // $consultation->new_date = $bienestar_news->bie_new_date;
            // $consultation->createdBy = $bienestar_news->per_name;
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
                    'cons_date' => 'date',
                    'cons_reason' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ÁÉÍÓÚÜáéíóúü\s]+$/',
                    'cons_description' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ÁÉÍÓÚÜáéíóúü\s]+$/',
                    'cons_weight' => 'required|integer',
                    'cons_height' => 'required|integer',
                    'cons_imc' => 'required|integer',
                    'cons_vaccination' => 'required|string|min:1|max:50|regex:/^[a-zA-Z0-9ÁÉÍÓÚÜáéíóúü\s]+$/',
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
