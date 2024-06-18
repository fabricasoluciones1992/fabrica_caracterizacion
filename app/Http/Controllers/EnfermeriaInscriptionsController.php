<?php

namespace App\Http\Controllers;

use App\Models\enfermeria_inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



class EnfermeriaInscriptionsController extends Controller
{
    public function index()
    {
        $enfIns = enfermeria_inscription::select();
        return response()->json([
            'status' => true,
            'data' => $enfIns
        ],200);
    }

    public function store(Request $request)
    {
        if ($request->acc_administrator == 1) {
            $rules = [

                'enf_ins_weight' => 'required|numeric|min:0|max:200',
                'enf_ins_height' => 'required|numeric|min:0|max:200',
                'enf_ins_imc' => 'required|numeric|min:0',
                'enf_ins_vaccination' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9nÑÁÉÍÓÚÜáéíóúü\s\-,.;]+$/',
                'per_id'=> 'required|unique:enfermeria_inscriptions|integer',

            ];

            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            }else{
                

                $enfIn = new enfermeria_inscription($request->input());
                $enfIn->save();
                Controller::NewRegisterTrigger("An insertion was made in the enfermeria inscriptions table'$enfIn->id'",3,$request->use_id);

                return response()->json([
                    'status' => True,
                    'message' => "The enfermeria inscriptions has been created successfully.",
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
        $enfIn = enfermeria_inscription::search($id);

        if ($enfIn == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched enfermeria inscriptions was not found']
            ], 400);
        } else {

            return response()->json([
                'status' => true,
                'data' => $enfIn
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        if ($request->acc_administrator == 1) {
            $enfIn = enfermeria_inscription::find($id);
            if ($enfIn == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The searched request was not found']
                ], 400);
            } else {
                $rules = [

                    'enf_ins_weight' => 'required|numeric|min:0|max:200',
                    'enf_ins_height' => 'required|numeric|min:0|max:200',
                    'enf_ins_imc' => 'required|numeric|min:0',
                'enf_ins_vaccination' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9nÑÁÉÍÓÚÜáéíóúü\s\-,.;]+$/',
                'per_id'=>'required|integer'

                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {


                    $enfIn->enf_ins_weight = $request->enf_ins_weight;
                    $enfIn->enf_ins_height = $request->enf_ins_height;
                    $enfIn->enf_ins_imc = $request->enf_ins_imc;
                    $enfIn->enf_ins_vaccination = $request->enf_ins_vaccination;
                    $enfIn->per_id = $request->per_id;


                    $enfIn->save();

                    Controller::NewRegisterTrigger("An update was made in the enfermeria inscriptions table", 4, $request->use_id);
                    return response()->json([
                    'status' => True,
                    'message' => "The enfermeria inscriptions has been updated successfully."
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
    public function lastDisease($id)
    {
        $lastDisease = enfermeria_inscription::lastDisease($id);

        return response()->json([
            'status' => true,
            'last_disease' => $lastDisease
        ], 200);
    }
    public function destroy(Request $request,$id)
    {
        return response()->json([
            'status' => false,
            'message' => 'function not available.'
        ]);
    }
}
