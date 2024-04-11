<?php

namespace App\Http\Controllers;

use App\Models\MonetaryState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class MonetaryStatesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        
        $monStates = MonetaryState::getbienestar_news();

        return response()->json([
            'status' => true,
            'data' => $monStates
        ], 200);
    
}

    public function store($proj_id,$use_id,Request $request)
    {
        

        if ($request->acc_administrator == 1) {
            $rules = [
                'mon_sta_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u|unique:monetary_states'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $monState = new MonetaryState($request->input());
                $monState->mon_sta_status=1;
                $monState->save();
                Controller::NewRegisterTrigger("An insertion was made in the monetary states table'$monState->mon_sta_id'", 3,$use_id);
                $id = $monState->mon_sta_id;
                $bienestar_news=MonetaryStatesController::Getbienestar_news($id);
                return response()->json([
                    'status' => True,
                    'message' => "The economic state type '".$monState->mon_sta_name."' has been created successfully.",
                    'data' => $bienestar_news

                ], 200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. This action can only be performed by active administrators.'
            ], 403); 
        }
    
}
public function Getbienestar_news($id)
{
    $mon_sta_id = $id;
    $bienestar_news = DB::table('bienestar_news')
        ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
        ->select('bie_new_date', 'persons.per_name')
        ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the monetary states table\'$mon_sta_id\''")
        ->get();

    if ($bienestar_news->count() > 0) {
        return $bienestar_news[0];
    } else {
        return null;
    }
}

    public function show($proj_id,$use_id,$id)
    {
         
        $monState = MonetaryState::find($id);
        $bienestar_news=MonetaryStatesController::Getbienestar_news($id);

        if ($monState == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested economic state was not found']
            ], 400);
        } else {
            $monState->new_date = $bienestar_news->bie_new_date;
                $monState->createdBy = $bienestar_news->per_name;
            return response()->json([
                'status' => true,
                'data' => $monState
            ]);
        }
    
}

    public function update($proj_id,$use_id,Request $request, $id)
    {
        
        $monState = MonetaryState::find($id);
        
        if ($request->acc_administrator == 1) {
            $monState = MonetaryState::find($id);
            if ($monState == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested economic state was not found']
                ], 400);
            } else {
                $rules = [
                    'mon_sta_name' =>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    $monState->mon_sta_name = $request->mon_sta_name;
                    $monState->save();
                    Controller::NewRegisterTrigger("An update was made in the monetary states table", 4,$use_id);

                    return response()->json([
                        'status' => True,
                        'message' => "The economic state '".$monState->mon_sta_name."' has been updated successfully."
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
        $monState = MonetaryState::find($id);
        
            $newMS=($monState->mon_sta_status==1)?0:1;
                $monState->mon_sta_status = $newMS;
                $monState->save();
                Controller::NewRegisterTrigger("An change status was made in the permanences table",2,$proj_id, $use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested economic state has been change status successfully'
                ]);
                
    }
}
