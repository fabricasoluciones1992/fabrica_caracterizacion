<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\ReasonType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ReasonsTypeController extends Controller
{
    public function index()
    {

        $reasonsT = ReasonType::all(); 
        return response()->json([
                'status' => true,
                'data' => $reasonsT
         ],200);
        
    }
    
    public function store(Request $request)
    {
            if ($request->acc_administrator == 1) {
                $rules = [

                    'rea_typ_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                    'rea_typ_type' => 'required|integer|in:0,1'

                    
                ];
                
                $validator = Validator::make($request->input(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $reasont = new ReasonType($request->input());
                    $reasont->save();
                    Controller::NewRegisterTrigger("An insertion was made in the reasons table'$reasont->rea_typ_id'",3,$request->use_id);
                    
                    return response()->json([
                        'status' => True,
                        'message' => "The reason type ".$reasont->rea_typ_name." has been successfully created.",

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

        $reasont = ReasonType::find($id);

            
            if ($reasont == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested reason was not found']
                ],400);
            }else{

                return response()->json([
                    'status' => true,
                    'data' => $reasont
                ]);
            }
        
    }
    public function update(Request $request, $id)
    {

        $reasont = ReasonType::find($id);
        if ($request->acc_administrator == 1) {
                $rules = [

                    'rea_typ_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                    'rea_typ_type' => 'required|integer|in:0,1'

                ];
                $validator = Validator::make($request->input(), $rules);
                $validate = Controller::validate_exists($request->rea_typ_name, 'reason_types', 'rea_typ_name', 'rea_typ_id', $id);

                if ($validator->fails()||$validate==0) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $reasont->rea_typ_name = $request->rea_typ_name;
                    $reasont->rea_typ_type = $request->rea_typ_type;

                    $reasont->save();
                    Controller::NewRegisterTrigger("An update was made in the reasons table",4,$request->use_id);

                    return response()->json([
                        'status' => True,
                        'data' => "The reason ".$reasont->rea_typ_name." has been successfully updated."
                    ],200);
                }
     }else {
        return response()->json([
            'status' => false,
            'message' => 'Access denied. This reason can only be performed by active administrators.'
        ], 403); 
    }
    }
        
    
    public function destroy(Request $request,$id)
    {

        
                return response()->json([
                    'status' => false,
                    'message' => 'Function not available'
                ]);
            
    }
}
