<?php

namespace App\Http\Controllers;

use App\Models\permanence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PermanencesController extends Controller
{
    public function index()
    {
        $permanences = permanence::select();

        return response()->json([
            'status' => true,
            'data' => $permanences
        ], 200);

}

    public function store(Request $request)
    {
        
        
        if ($request->acc_administrator == 1) {
            $rules = [

                'perm_date' =>'required|date',
                'perm_description' => 'required|string|min:1|max:50',
                'emp_id' =>'required|exists:employees|integer|min:1',
                'perm_status'=>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
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
                $currentDate = now()->toDateString();
                $request->merge(['perm_date' => $currentDate]);

                $permanence = new Permanence($request->input());
                $permanence->save();
                Controller::NewRegisterTrigger("An insertion was made in the permanences table'$permanence->perm_id'", 3,$request->use_id);
                // $id = $permanence->perm_id;
                // $bienestar_news=PermanencesController::Getbienestar_news($id);
                return response()->json([
                    'status' => True,
                    'message' => "The permanences has been created successfully.",
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
        
        $permanences =  permanence::find($id);

        if ($permanences == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched permanences was not found']
            ], 400);
        } else {
          
            return response()->json([
                'status' => true,
                'data' => $permanences
            ]);
        }

    
}

    public function update(Request $request, $id)
    {
        
        $permanences = permanence::find($id);
        
        if ($request->acc_administrator == 1) {
            $permanences = Permanence::find($id);
            if ($permanences == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The searched permanence was not found']
                ], 400);
            } else {
                $rules = [

                    'perm_date' =>'required|date',
                    'emp_id' =>'required|exists:employees|integer|min:1',
                    'perm_description' => 'required|string|min:1|min:50',
                    'sol_id' =>'required|exists:solicitudes|integer|min:1',
                    'act_id' =>'required|exists:actions|integer|min:1',
                    'perm_status'=>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    Controller::NewRegisterTrigger("An update was made in the permanences table", 4,$request->use_id);

                    $permanences->perm_date = $request->perm_date;
                    $permanences->perm_description = $request->perm_description;
                    $permanences->emp_id = $request->emp_id;

                    $permanences->sol_id = $request->sol_id;
                    $permanences->perm_status = $request->perm_status;

                    $permanences->act_id = $request->act_id;
                    $permanences->save();
                    return response()->json([
                        'status' => True,
                        'message' => "The permanence has been updated successfully."
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
        $permanences = permanence::find($id);
        $newPerm=($permanences->perm_status == 1)?0:1;
                $permanences->perm_status = $newPerm;
                $permanences->save();
                Controller::NewRegisterTrigger("An change status was made in the permanences table",2,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested permanence has been change status successfully'
                ]);
            
    }

  
public function filtredforTSolicitud($id,$sol_typ_name)
{
    try {
        $permanences = permanence::findBySolTyp($id,$sol_typ_name);
        
        
        return response()->json([
            'status' => true,
            'data' => $permanences
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
        $permanences = permanence::findByPsol($id);
        
        
        return response()->json([
            'status' => true,
            'data' => $permanences
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
}


}
