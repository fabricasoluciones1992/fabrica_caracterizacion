<?php

namespace App\Http\Controllers;

use App\Models\Permanence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PermanencesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        $permanences = Permanence::select();
        Controller::NewRegisterTrigger("A search was performed in the permanences table", 1, $proj_id, $use_id);

        return response()->json([
            'status' => true,
            'data' => $permanences
        ], 200);

}

    public function store($proj_id,$use_id,Request $request)
    {
        
        
        if ($request->acc_administrator == 1) {
            $rules = [
                'perm_date' =>'required|date',
                'perm_description' =>'required|string|min:1|max:50|/^[a-zA-Z0-9\s]+$/',
                'perm_responsible' =>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                'sol_id' =>'required|integer|max:1',
                'act_id' =>'required|integer|max:1'
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

                $permanences = new Permanence($request->input());
                $permanences->save();
                Controller::NewRegisterTrigger("An insertion was made in the permanences table", 3, $proj_id, $use_id);

                return response()->json([
                    'status' => True,
                    'message' => "The permanences has been created successfully."
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
        
        $permanences =  Permanence::find($id);
        if ($permanences == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched permanences was not found']
            ], 400);
        } else {
            Controller::NewRegisterTrigger("A search was performed in the permanences table", 4,$proj_id, $use_id);

            return response()->json([
                'status' => true,
                'data' => $permanences
            ]);
        }

    
}

    public function update($proj_id,$use_id,Request $request, $id)
    {
        
        $permanences = Permanence::find($id);
        
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
                    'perm_description' =>'required|string|min:1|max:50|/^[a-zA-Z0-9\s]+$/',
                    'sol_id' =>'required|integer|max:1',
                    'act_id' =>'required|integer|max:1'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    Controller::NewRegisterTrigger("An update was made in the permanences table", 1, $proj_id, $use_id);

                    $permanences->perm_date = $request->perm_date;
                    $permanences->perm_description = $request->perm_description;
                    $permanences->req_id = $request->req_id;
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

    public function destroy($proj_id,$use_id, $id)
    {
        $permanences = Permanence::find($id);
        
            if ($permanences->perm_status == 1){
                $permanences->perm_status = 0;
                $permanences->save();
                Controller::NewRegisterTrigger("An delete was made in the permanences table",2,$proj_id, $use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested permanence has been disabled successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The requested permanence has already been disabled previously'
                ]);
            } 
    }

    public function filtredforPermanence($proj_id, $use_id, $id)
{
    try {
        $permanences = Permanence::findByDocument($id);
        
        Controller::NewRegisterTrigger("Se realizó una búsqueda en la tabla permanences", 1, $proj_id, $use_id);
        
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
public function filtredforTSolicitud($proj_id, $use_id, $id)
{
    try {
        $permanences = Permanence::findBySolTyp($id);
        
        Controller::NewRegisterTrigger("Se realizó una búsqueda en la tabla permanences", 1, $proj_id, $use_id);
        
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
