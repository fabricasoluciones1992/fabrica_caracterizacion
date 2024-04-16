<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssistancesController extends Controller
{
    public function index()
    {
        
        $assistances = Assistance::select();

        return response()->json([
            'status' => true,
            'data' => $assistances
        ],200);
    

    }
    public function store($proj_id,$use_id,Request $request)
    {
        
        if ($request->acc_administrator == 1) {
            $rules = [
                'ass_date' =>'date',
                'stu_id' =>'required|integer',
                'per_id' =>'required|integer',
                'bie_act_id' =>'required|integer'
            ];

            $validator = Validator::make($request->input(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $currentDate = now()->toDateString();

                $request->merge(['ass_date' => $currentDate]);

                $assistances = new assistance($request->input());
                $assistances->ass_status=0;

                $assistances->save();

                Controller::NewRegisterTrigger("An insertion was made in the assistences table'$assistances->ass_id'",3, $use_id);
                // $id = $assistances->ass_id;
                // $bienestar_news=AssistancesController::Getbienestar_news($id);

                return response()->json([
                    'status' => True,
                    'message' => "The assistance has been created successfully.",

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
    $ass_id = $id;
    $bienestar_news = DB::table('bienestar_news')
        ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
        ->select('bie_new_date', 'persons.per_name')
        ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the Actions table\'$ass_id\''")
        ->get();

    if ($bienestar_news->count() > 0) {
        return $bienestar_news[0];
    } else {
        return null;
    }
}

    public function show($id)
    {
        
        $assistances =  Assistance::find($id);
        // $bienestar_news=AssistancesController::Getbienestar_news($id);

        if ($assistances == null) {

            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched assistance was not found']
            ],400);
        }else{
            // $assistances->new_date = $bienestar_news->bie_new_date;
            // $assistances->createdBy = $bienestar_news->per_name;
            return response()->json([
                'status' => true,
                'data' => $assistances
            ]);
        }
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        
        $assistances = assistance::find($id);
        
        if ($_SESSION['acc_administrator'] == 1) {
            $assistances = assistance::find($id);
            if ($assistances == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The searched assistance was not found']
                ],400);
            } else {
                $rules = [
                    'ass_date' =>'date',
                    'ass_status' =>'required|integer|max:1',
                    'stu_id' =>'required|integer|max:1',
                    'bie_act_id' =>'required|integer|max:1',
                    'per_id' =>'required|integer|max:1'

                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    $currentDate = now()->toDateString();

                    $request->merge(['ass_date' => $currentDate]);

                    $assistances->ass_date = $request->ass_date;
                    $assistances->ass_status = $request->ass_status;
                    $assistances->stu_id = $request->stu_id;
                    $assistances->bie_act_id = $request->bie_act_id;
                    $assistances->save();
                    Controller::NewRegisterTrigger("An update was made in the assistences table",4,$use_id);

                    return response()->json([
                        'status' => True,
                        'message' => "The assistance has been updated."
                    ],200);
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
        $assistances = assistance::find($id);
                $newAss=($assistances->ass_status==1) ? 0:1;
                $assistances->ass_status =$newAss;
                $assistances->save();
                Controller::NewRegisterTrigger("An change status was made in the actions table",2,$use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested assistances has been change successfully'
                ]);
            

    }
}
