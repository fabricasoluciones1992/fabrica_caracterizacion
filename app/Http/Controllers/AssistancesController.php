<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use App\Models\BienestarActivity;
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
    public function store(Request $request)//un estudiante se puede registrar solo una vez a una actividad
    {
            $rules = [ 
                'bie_act_id' =>'required|integer',
                'use_id' =>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $student = DB::select("SELECT * FROM students WHERE ? = per_id", [$request->use_id]);
                if ($student == []) {
                    return response()->json([
                        'status' => false,
                        'message' => 'The user who is registering is not a student'
                    ]);
                }
                $currentDate = now()->toDateString();
                $request->merge(['ass_date' => $currentDate]);
                $assistances = new assistance($request->input());
                $assistances->ass_status=0;
                $assistances->ass_reg_status = 1;
                $assistances->stu_id = $student[0]->stu_id;
                $assistances->save();
                Controller::NewRegisterTrigger("An insertion was made in the assistences table'$assistances->ass_id'",3, $request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => "The assistance has been created successfully.",

                ], 200);
            }

    
}

    public function show($id)
    {
        
        $assistances =  Assistance::find($id);


        if ($assistances == null) {

            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched assistance was not found']
            ],400);
        }else{
 
            return response()->json([
                'status' => true,
                'data' => $assistances
            ]);
        }
    }
    public function update(Request $request, $id)
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
                    Controller::NewRegisterTrigger("An update was made in the assistences table",4,$request->use_id);

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

    public function destroy(Request $request,$id)
    {
        $assistances = assistance::find($id);
                $newAss=($assistances->ass_status==1) ? 0:1;
                $assistances->ass_status =$newAss;
                $assistances->save();
                Controller::NewRegisterTrigger("An change status was made in the actions table",2,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested assistances has been change successfully'
                ]);
            

    }
    public function destroyAR(Request $request,$id)
    {
        $assistances = assistance::find($id);
        $newAsg=($assistances->ass_reg_status==1)?0:1;
                $assistances->ass_reg_status =$newAsg;
                $assistances->save();
                Controller::NewRegisterTrigger("An change status was made in the bienestar activity type table",2,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested asisstance register has been change successfully'
                ]);
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $file->storeAs('csv', $file->getClientOriginalName());

            $csvData = array_map('str_getcsv', file($file->path()));

            foreach ($csvData as $row) {
                
                $assistance = new Assistance();
                $assistance->ass_date = date('Y-m-d');
                $assistance->ass_status = 1;
                $assistance->stu_id = intval($row[0]);
                $assistance->bie_act_id = $request->bie_act_id;
                $assistance->ass_reg_status = 1;
                $assistance->save();
            }
        }else{
            return response()->json(['error' => 'No CSV file found in request'], 400);
        }

    }
}
