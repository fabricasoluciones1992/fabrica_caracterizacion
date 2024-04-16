<?php

namespace App\Http\Controllers;

use App\Models\HistoryConsultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HistoryConsultationsController extends Controller
{
    public function index()
    {
        $histcon = HistoryConsultation::select();
        return response()->json([
            'status' => true,
            'data' => $histcon
        ], 200);
    }
    public function store( Request $request)
    {
        if ($request->acc_administrator == 1) {
            $rules = [
                'cons_id' =>'required|integer|min:1|max:999999',
                'per_id' =>'required|integer|min:1|max:999999',
            ];
            $validator = Validator::make($request->all(), $rules); 
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $hitcon = new HistoryConsultation($request->input());
                $hitcon->save();
                Controller::NewRegisterTrigger("An insertion was made in the History consultations table'$hitcon->his_con_id'", 3, $request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => "The History consultations for the student id: '".$hitcon->cons_id."' has been created successfully.",
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
    //     $his_con_id = $id;
    //     $bienestar_news = DB::table('bienestar_news')
    //         ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
    //         ->select('bie_new_date', 'persons.per_name')
    //         ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the History consultations table\'$his_con_id\''")
    //         ->get();
    
    //     if ($bienestar_news->count() > 0) {
    //         return $bienestar_news[0];
    //     } else {
    //         return null;
    //     }
    // }

    public function show($id)
    {
        $hitcon = HistoryConsultation::find($id);
        // $bienestar_news=HistoryConsultationsController::Getbienestar_news($id);

        if ($hitcon == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested History consultations was not found']
            ], 400);
        } else {
            // $hitcon->new_date = $bienestar_news->bie_new_date;
            // $hitcon->createdBy = $bienestar_news->per_name;
            return response()->json([
                'status' => true,
                'data' => $hitcon
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        return response()->json([
            'status' => false,
            'message' => 'Function not available'
        ]);
    }
    public function destroy(Request $request,$id)
    {
        return response()->json([
            'status' => false,
            'message' => 'Function not available'
        ]);
    }
}
