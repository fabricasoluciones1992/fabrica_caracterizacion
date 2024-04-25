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
            'enf_ins_weight' => 'required|integer',
            'enf_ins_height' => 'required|integer',
            'enf_ins_vaccination' => 'required|string|min:1|max:50|regex:/^[a-zA-Z0-9nÑÁÉÍÓÚÜáéíóúü\s]+$/',
            'per_id'=> 'required|integer',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $peso = $request->enf_ins_weight;
            $altura = $request->enf_ins_height / 100;
            $imc = $peso / ($altura * $altura);
            
            if ($imc < 18.5) {
                $categoria_imc = "Bajo";
            } elseif ($imc >= 18.5 && $imc < 25) {
                $categoria_imc = "Normal";
            } elseif ($imc >= 25 && $imc < 30) {
                $categoria_imc = "Sobrepeso";
            } else {
                $categoria_imc = "Obesidad";
            }
            
            return response()->json([
                'status' => True,
                'message' => "The enfermeria inscriptions has been created successfully.",
                'imc_category' => $categoria_imc 
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
//         ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the enfermeria_inscriptions table\'$cons_id\''")
//         ->get();

//     if ($bienestar_news->count() > 0) {
//         return $bienestar_news[0];
//     } else {
//         return null;
//     }
// }
    public function show($id)
    {
        $enfIn = enfermeria_inscription::find($id);
        // $bienestar_news=enfermeria_inscriptionController::Getbienestar_news($id);

        if ($enfIn == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched enfermeria inscriptions was not found']
            ], 400);
        } else {
            // $enfIn->new_date = $bienestar_news->bie_new_date;
            // $enfIn->createdBy = $bienestar_news->per_name;
            return response()->json([
                'status' => true,
                'data' => $enfIn
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        if ($request->acc_administrator == 1) {
            $enfIn = enfermeria_inscription::search($id);
            if ($enfIn == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The searched request was not found']
                ], 400);
            } else {
                $rules = [
                'enf_ins_weight' => 'required|integer',
                'enf_ins_height' => 'required|integer',
                'enf_ins_imc' => 'required|integer',
                'enf_ins_vaccination' => 'required|string|min:1|max:50|regex:/^[a-zA-Z0-9nÑÁÉÍÓÚÜáéíóúü\s]+$/',
                'per_id'=>'required|integer',
                
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
    public function destroy(Request $request,$id)
    {
        return response()->json([
            'status' => false,
            'message' => 'function not available.'
        ]);
    }
}
