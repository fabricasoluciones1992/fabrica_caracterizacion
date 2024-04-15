<?php

namespace App\Http\Controllers;

use App\Models\permanence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PermanencesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        $permanences = permanence::select();

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
                'perm_description' =>'required|string|min:1|max:50|regex:/^[a-zA-Z0-9ÑñÁÉÍÓÚÜáéíóúü\s]+$/u',                
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

                $permanence = new Permanence($request->input());
                $permanence->perm_status=1;
                $permanence->save();
                Controller::NewRegisterTrigger("An insertion was made in the permanences table'$permanence->perm_id'", 3,$use_id);
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
// public function Getbienestar_news($id)
// {
//     $perm_id = $id;
//     $bienestar_news = DB::table('bienestar_news')
//         ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//         ->select('bie_new_date', 'persons.per_name')
//         ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the permanences table\'$perm_id\''")
//         ->get();

//     if ($bienestar_news->count() > 0) {
//         return $bienestar_news[0];
//     } else {
//         return null;
//     }
// }

    public function show($proj_id,$use_id,$id)
    {
        
        $permanences =  permanence::find($id);
        // $bienestar_news=PermanencesController::Getbienestar_news($id);

        if ($permanences == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched permanences was not found']
            ], 400);
        } else {
            // $permanences->new_date = $bienestar_news->bie_new_date;
            // $permanences->createdBy = $bienestar_news->per_name;
            return response()->json([
                'status' => true,
                'data' => $permanences
            ]);
        }

    
}

    public function update($proj_id,$use_id,Request $request, $id)
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
                    Controller::NewRegisterTrigger("An update was made in the permanences table", 4,$use_id);

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
        $permanences = permanence::find($id);
        $newPerm=($permanences->perm_status == 1)?0:1;
                $permanences->perm_status = $newPerm;
                $permanences->save();
                Controller::NewRegisterTrigger("An change status was made in the permanences table",2,$use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested permanence has been change status successfully'
                ]);
            
    }

    public function filtredforDocument($proj_id, $use_id, $id)
{
    try {
        $permanences = Controller::findByDocument($id);
        
        
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
        $permanences = permanence::findBySolTyp($id);
        
        
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
