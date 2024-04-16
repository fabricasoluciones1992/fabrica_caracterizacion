<?php

namespace App\Http\Controllers;

use App\Models\BienestarActivity;
use App\Models\BienestarActivityTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BienestarActivityTypesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        
            $bienestarActTypes = BienestarActivityTypes::all();

            return response()->json([
                'status' => true,
                'data' => $bienestarActTypes
            ],200);
        
    }
    public function store($proj_id,$use_id,Request $request)
    {
        
            if ($request->acc_administrator == 1) {
                $rules = [
                    'bie_act_typ_name' => 'required|string|min:1|max:55|unique:bienestar_activity_types|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    $bienestarActType = new BienestarActivityTypes($request->input());
                    $bienestarActType->bie_act_typ_status=1;
                    $bienestarActType->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Bienestar Activities types table'$bienestarActType->bie_act_typ_id'",3,$use_id);
                    // $id = $bienestarActType->bie_act_typ_id;
                    // $bienestar_news=BienestarActivityTypesController::Getbienestar_news($id);
                    return response()->json([
                        'status' => true,
                        'message' => "The bienestar activity type '".$bienestarActType->bie_act_typ_name."' has been created successfully.",

                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This action can only be performed by active administrators.'
                ], 403); 
            }
        
    }
//     public function Getbienestar_news($id)
// {
//     $bie_act_typ_id = $id;
//     $bienestar_news = DB::table('bienestar_news')
//         ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//         ->select('bie_new_date', 'persons.per_name')
//         ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the Bienestar Activities types table\'$bie_act_typ_id\''")
//         ->get();

//     if ($bienestar_news->count() > 0) {
//         return $bienestar_news[0];
//     } else {
//         return null;
//     }
// }
    public function show($proj_id,$use_id,$id)
    {
        
            $bienestarActType = BienestarActivity::category($id);
            // $bienestar_news=BienestarActivityTypesController::Getbienestar_news($id);

            if ($bienestarActType == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested bienestar activity type was not found']
                ],400);
            } else {
                // $bienestarActType->new_date = $bienestar_news->bie_new_date;
                // $bienestarActType->createdBy = $bienestar_news->per_name;
                return response()->json([
                    'status' => true,
                    'data' => $bienestarActType
                ]);
            }
        
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        
        $bienestarActTypes = BienestarActivityTypes::find($id);
 
            if ($request->acc_administrator == 1) {
                $bienestarActTypes = BienestarActivityTypes::find($id);
                if ($bienestarActTypes == null) {
                    return response()->json([
                        'status' => false,
                        'data' => ['message' => 'The requested bienestar activity type was not found']
                    ],400);
                } else {
                    $rules = [
                        'bie_act_typ_name' =>'required|string|min:1|max:55|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'

                    ];
                    $validator = Validator::make($request->input(), $rules);
                    if ($validator->fails()) {
                        return response()->json([
                            'status' => False,
                            'message' => $validator->errors()->all()
                        ]);
                    } else {
                        $bienestarActTypes->bie_act_typ_name = $request->bie_act_typ_name;
                        $bienestarActTypes->bie_act_typ_status=1;
                        $bienestarActTypes->save();
                        Controller::NewRegisterTrigger("An update was made in the Bienestar Activities types table",4,$use_id);

                        return response()->json([
                        'status' => True,
                            'data' => "The bienestar activity type ".$bienestarActTypes->bie_act_typ_name." has been updated successfully."
                        ],200);
                    };
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

        $bienestarActTypes = BienestarActivityTypes::find($id);
        
                $newBiAct=($bienestarActTypes->bie_act_typ_status==1)?0:1;
                $bienestarActTypes->bie_act_typ_status =$newBiAct ;
                $bienestarActTypes->save();
                Controller::NewRegisterTrigger("An change status was made in the bienestar activity type table",2,$use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested bienestar activity type has been change successfully'
                ]);
    
        
    }
}
