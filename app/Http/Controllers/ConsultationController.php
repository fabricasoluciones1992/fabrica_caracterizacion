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
        $consultations = DB::select("
        SELECT
            consultations.*,
            ViewPersons.per_id,
            ViewPersons.per_name,
            ViewPersons.per_lastname,
            ViewPersons.per_document,
            ViewPersons.per_expedition,
            ViewPersons.per_birthdate,
            ViewPersons.per_direction,
            ViewPersons.per_rh,
            ViewPersons.civ_sta_id,
            ViewPersons.mul_id,
            ViewPersons.doc_typ_id,
            ViewPersons.doc_typ_name,
            ViewPersons.use_id,
            ViewPersons.eps_id,
            ViewPersons.gen_id

        FROM consultations
        INNER JOIN ViewPersons ON consultations.per_id = ViewPersons.per_id
    ");        return response()->json([
            'status' => true,
            'data' => $consultations
        ],200);
    }
    public function store(Request $request)
    {
        if ($request->acc_administrator == 1) {
            date_default_timezone_set('America/Bogota');
            $rules = [

                'cons_reason' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ñÑÁÉÍÓÚÜáéíóúü\s\-,.;\/]+$/',
                'cons_description' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ñÑÁÉÍÓÚÜáéíóúü\s\-,.;\/]+$/',
                'per_id' => 'required|exists:persons|integer',
                'use_id' => 'required|exists:users'
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
                $consultation->cons_time = date('H:i:s');
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
            date_default_timezone_set('America/Bogota');
            $consultation = Consultation::find($id);
            if ($consultation == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The searched request was not found']
                ], 400);
            } else {
                $rules = [
                    'cons_reason' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ñÑÁÉÍÓÚÜáéíóúü\s\-,.;\/]+$/',
                    'cons_description' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ñÑÁÉÍÓÚÜáéíóúü\s\-,.;\/]+$/',
                    'per_id' => 'required|exists:persons|integer',
                    'use_id' => 'required|exists:users'

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
