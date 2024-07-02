<?php

namespace App\Http\Controllers;

use App\Models\Permanence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PermanencesController extends Controller
{
    public function index()
    {
        $Permanences = Permanence::select();

        return response()->json([
            'status' => true,
            'data' => $Permanences
        ], 200);

}

    public function store(Request $request)
    {
        
        
        if ($request->acc_administrator == 1) {
            $rules = [

                'perm_description' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ñÑÁÉÍÓÚÜáéíóúü\s\-,.;\/]+$/',
                'emp_id' =>'required|exists:employees|integer|min:1',
                'perm_status'=>'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                'sol_id' =>'required|exists:solicitudes|integer|min:1',
                'act_id' =>'required|exists:actions|integer|min:1',
            ];            
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                

                $Permanence = new Permanence($request->input());
                $Permanence->perm_date = now()->toDateString(); 

                $Permanence->save();
                Controller::NewRegisterTrigger("An insertion was made in the Permanences table'$Permanence->perm_id'", 3,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => "The Permanences has been created successfully.",
                ], 200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. This action can only be performed by active administrators.'
            ], 403); 
        }
    
}


    public function show($id)
    {
        
        $Permanences =  Permanence::search($id);

        if ($Permanences == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched Permanences was not found']
            ], 400);
        } else {
          
            return response()->json([
                'status' => true,
                'data' => $Permanences
            ]);
        }

    
}

    public function update(Request $request, $id)
    {
        
        $Permanences = Permanence::find($id);
        
        if ($request->acc_administrator == 1) {
            $Permanences = Permanence::find($id);
            if ($Permanences == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The searched Permanence was not found']
                ], 400);
            } else {
                $rules = [

                    'emp_id' =>'required|integer|min:1',
                    'perm_description' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9ñÑÁÉÍÓÚÜáéíóúü\s\-,.;¿?:()\/]+$/',
                    'sol_id' =>'required|integer|min:1',
                    'act_id' =>'required|integer|min:1',
                    'perm_status'=>'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                ];
                $validator = Validator::make($request->input(), $rules);
                
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    Controller::NewRegisterTrigger("An update was made in the Permanences table", 4,$request->use_id);

                    
                    $Permanences->perm_description = $request->perm_description;
                    $Permanences->emp_id = $request->emp_id;
                    $Permanences->perm_date = now()->toDateString(); 

                    $Permanences->sol_id = $request->sol_id;
                    $Permanences->perm_status = $request->perm_status;

                    $Permanences->act_id = $request->act_id;
                    $Permanences->save();
                    return response()->json([
                        'status' => True,
                        'message' => "The Permanence has been updated successfully."
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
        $Permanences = Permanence::find($id);
        $newPerm=($Permanences->perm_status == 1)?0:1;
                $Permanences->perm_status = $newPerm;
                $Permanences->save();
                Controller::NewRegisterTrigger("An change status was made in the Permanences table",2,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested Permanence has been change status successfully'
                ]);
            
    }

  
public function filtredforTSolicitud($id,$sol_typ_name)
{
    try {
        $Permanences = Permanence::findBySolTyp($id,$sol_typ_name);
        
        
        return response()->json([
            'status' => true,
            'data' => $Permanences
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
}
public function filtredPsolicitud($id)
{
    try {
        $Permanences = Permanence::findByPsol($id);
        
        
        return response()->json([
            'status' => true,
            'data' => $Permanences
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
}


}
