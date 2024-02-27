<?php

namespace App\Http\Controllers;

use App\Models\Allergie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AllergiesController extends Controller
{
    public function index($proj_id,$use_id)
    {

        $allergie = Allergie::all();
              
        Controller::NewRegisterTrigger("A search was performed on the Allergies table",4,$proj_id, $use_id);
        return response()->json([
                'status' => true,
                'data' => $allergie
         ],200);
        
    }
    public function store($proj_id,$use_id,Request $request)
    {
        
            if ($request->acc_administrator == 1) {
                $rules = [
                    'all_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                    
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $allergie = new Allergie($request->input());
                    $allergie->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Allergies table",3,$proj_id, $use_id);
        
                    return response()->json([
                        'status' => True,
                        'message' => "The Allergie type ".$allergie->all_name." has been successfully created."
                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This Allergie can only be performed by active administrators.'
                ], 403); 
            }
        
    }
    public function show($proj_id,$use_id,$id)
    {

        $allergie = Allergie::find($id);
        
            
            if ($allergie == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested Allergie was not found']
                ],400);
            }else{
                Controller::NewRegisterTrigger("A search was performed on the Allergies table",4,$proj_id, $use_id);

                return response()->json([
                    'status' => true,
                    'data' => $allergie
                ]);
            }
        
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {

        $allergie = Allergie::find($id);
        if ($request->acc_administrator == 1) {
                $rules = [
                    'all_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $allergie->all_name = $request->all_name;
                    $allergie->save();
                    Controller::NewRegisterTrigger("An update was made in the Allergies table",1,$proj_id, $use_id);

                    return response()->json([
                        'status' => True,
                        'data' => "The Allergie ".$allergie->all_name." has been successfully updated."
                    ],200);
                }
     }else {
        return response()->json([
            'status' => false,
            'message' => 'Access denied. This Allergie can only be performed by active administrators.'
        ], 403); 
    }
    }
        
    
    public function destroy($proj_id,$use_id, $id)
    {

        $allergie = Allergie::find($id);
        
            
                return response()->json([
                    'status' => false,
                    'message' => 'The requested Allergie has already been disabled previously'
                ]);
             
    }
}