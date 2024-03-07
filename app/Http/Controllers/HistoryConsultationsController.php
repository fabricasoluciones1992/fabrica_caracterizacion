<?php

namespace App\Http\Controllers;

use App\Models\HistoryConsultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HistoryConsultationsController extends Controller
{
    public function index($proj_id,$use_id)
    {
        $histcon = HistoryConsultation::select();
        Controller::NewRegisterTrigger("A search was performed in the History consultations table", 4, $proj_id, $use_id);
        return response()->json([
            'status' => true,
            'data' => $histcon
        ], 200);
    }
    public function store($proj_id,$use_id, Request $request)
    {
        if ($request->acc_administrator == 1) {
            $rules = [
                'cons_id' =>'required|integer|min:1|max:999999',
                'stu_id' =>'required|integer|min:1|max:999999',
            ];
            $validator = HistoryConsultation::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $histcon = new HistoryConsultation($request->input());
                $histcon->save();
                Controller::NewRegisterTrigger("An insertion was made in the History consultations table", 3,$proj_id, $use_id);

                return response()->json([
                    'status' => True,
                    'message' => "The History consultations for the student id: '".$histcon->cons_id."' has been created successfully."
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
        $histcon = HistoryConsultation::find($id);
        if ($histcon == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested History consultations was not found']
            ], 400);
        } else {
            Controller::NewRegisterTrigger("A search was performed in the History consultations table table", 4, $proj_id, $use_id);

            return response()->json([
                'status' => true,
                'data' => $histcon
            ]);
        }
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        $histcon = HistoryConsultation::find($id);
        
        if ($request->acc_administrator == 1) {
            $histcon = HistoryConsultation::find($id);
            if ($histcon == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested economic state was not found']
                ], 400);
            } else {
                $rules = [
                    'cons_id' =>'required|integer|min:1|max:999999',
                    'stu_id' =>'required|integer|min:1|max:999999',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    Controller::NewRegisterTrigger("An update was made in the permanences table", 1, $proj_id, $use_id);

                    $histcon->cons_id = $request->cons_id;
                    $histcon->stu_id = $request->stu_id;
                    $histcon->save();
                    return response()->json([
                        'status' => True,
                        'message' => "The history consultations has been updated successfully."
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
        return response()->json([
            'status' => false,
            'message' => 'No existe la funcion...aun'
        ]);
        /*$histcon = HistoryConsultation::find($id);
        
        if ($histcon->perm_status == 1){
            $histcon->perm_status = 0;
            $histcon->save();
            Controller::NewRegisterTrigger("An delete was made in the history consultations table",2,$proj_id, $use_id);
            return response()->json([
                'status' => True,
                'message' => 'The requested history consultations has been disabled successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'The requested history consultations has already been disabled previously'
            ]);
        }*/
    }
}
