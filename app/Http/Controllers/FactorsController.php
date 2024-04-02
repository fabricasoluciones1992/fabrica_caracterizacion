<?php

namespace App\Http\Controllers;

use App\Models\factor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FactorsController extends Controller
{
    public function index($proj_id,$use_id)
    {
        
        
        $factors = factor::all();
        Controller::NewRegisterTrigger("A search was performed in the factors table", 1, $proj_id, $use_id);
        return response()->json([
            'status' => true,
            'data' => $factors,
        ], 200);

    
}
    public function store($proj_id,$use_id,Request $request)
    {
        
        
        if ($request->acc_administrator == 1) {
            $rules = [
                'fac_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([

                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            } else {
                $factor = new factor($request->input());
                $factor->fac_status=1;
                $factor->save();
                Controller::NewRegisterTrigger("An insertion was made in the factors table", 3 ,$proj_id, $use_id);

                return response()->json([
                'status' => True,
                'message' => "The factor type '".$factor->fac_name."' has been created successfully."
                ], 200);
            }
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Access denied. This action can only be performed by active administrators.'
            ], 403); 
        }
    
}
    public function show($proj_id,$use_id,$id)
    {
        
            $factor = factor::find($id);
            if ($factor == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested factor was not found']
                ], 400);
            } else {
                Controller::NewRegisterTrigger("A search was performed in the factors table", 4 ,$proj_id, $use_id);

                return response()->json([
                    'status' => true,
                    'data' => $factor
                ]);
            }
        
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        
        $factor = factor::find($id);
        
        if ($request->acc_administrator == 1) {
            $factor = factor::find($id);
            if ($factor == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested factor was not found']
                ], 400);
            } else {
                $rules = [
                    'fac_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                    ]);
                } else {
                    $factor->fac_name = $request->fac_name;
                    $factor->save();
                    Controller::NewRegisterTrigger("An update was made in the factors table", 1 ,$proj_id, $use_id);

                    return response()->json([
                'status' => True,
                    'data' => "The factor '".$factor->fac_name."' has been updated successfully."
                    ], 200);
                };
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. This action can only be performed by active administrators.'
            ], 403); 
        }
    
}
    public function destroy($proj_id,$use_id, string $id)
    {

        $factor = factor::find($id);
        
            if ($factor->rea_status == 1){
                $factor->rea_status = 0;
                $factor->save();
                Controller::NewRegisterTrigger("An delete was made in the factors table",2,$proj_id, $use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested factor has been disabled successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The requested factor has already been disabled previously'
                ]);
            }  
    }
    
}