<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use App\Models\Disease;
use Illuminate\Http\Request;

class DiseasesController extends Controller
{
    public function index($proj_id,$use_id)
    {

        $disease = Disease::all();
              
        Controller::NewRegisterTrigger("A search was performed on the Diseases table",4,$proj_id, $use_id);
        return response()->json([
                'status' => true,
                'data' => $disease
         ],200);
        
    }
    public function store($proj_id,$use_id,Request $request)
    {
        
            if ($request->acc_administrator == 1) {
                $rules = [
                    'dis_name' => 'required|string|min:1|max:50|regex:/^[a-zA-Z-ÁÉÍÓÚÜáéíóúü\s]+$/'
                    
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $disease = new Disease($request->input());
                    $disease->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Diseases table",3,$proj_id, $use_id);
        
                    return response()->json([
                        'status' => True,
                        'message' => "The Disease type ".$disease->dis_name." has been successfully created."
                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This Disease can only be performed by active administrators.'
                ], 403); 
            }
        
    }
    public function show($proj_id,$use_id,$id)
    {

        $disease = Disease::find($id);
        
            
            if ($disease == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested Disease was not found']
                ],400);
            }else{
                Controller::NewRegisterTrigger("A search was performed on the Diseases table",4,$proj_id, $use_id);

                return response()->json([
                    'status' => true,
                    'data' => $disease
                ]);
            }
        
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {

        $disease = Disease::find($id);
        if ($request->acc_administrator == 1) {
                $rules = [
                    'dis_name' => 'required|string|min:1|max:50|regex:/^[a-zA-Z-ÁÉÍÓÚÜáéíóúü\s]+$/'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $disease->dis_name = $request->dis_name;
                    $disease->save();
                    Controller::NewRegisterTrigger("An update was made in the Diseases table",1,$proj_id, $use_id);

                    return response()->json([
                        'status' => True,
                        'data' => "The Disease ".$disease->dis_name." has been successfully updated."
                    ],200);
                }
     }else {
        return response()->json([
            'status' => false,
            'message' => 'Access denied. This Disease can only be performed by active administrators.'
        ], 403); 
    }
    }
        
    
    public function destroy($proj_id,$use_id, $id)
    {

        $disease = Disease::find($id);
        
            
                return response()->json([
                    'status' => false,
                    'message' => 'The requested Disease has already been disabled previously'
                ]);
             
    }
}
