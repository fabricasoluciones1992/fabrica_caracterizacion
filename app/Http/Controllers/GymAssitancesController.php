<?php

namespace App\Http\Controllers;
use App\Models\Gym_assistance;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GymAssitancesController extends Controller
{
    public function index()
{
    $gymAss = Gym_assistance::select();
    return response()->json([
        'status' => true,
        'data' => $gymAss
    ], 200);
}


public function store(Request $request)
{
    if ($request->acc_administrator == 1) {
        $rules = [
            'per_id' => 'required|exists:persons|integer'
        ];
        
        $validator = Validator::make($request->input(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $isEnrolled = DB::table('gym_inscriptions')
                            ->where('per_id', $request->per_id)
                            ->where('gym_ins_status', '1')
                            ->exists();
            
            if (!$isEnrolled) {
                return response()->json([
                    'status' => False,
                    'message' => 'User is not enrolled in the gym. Cannot register assistance.'
                ], 400);
            }

            $gymAs = new Gym_assistance($request->input());
            $gymAs->gym_ass_date = now()->toDateString(); 
            $gymAs->gym_ass_start = now()->toTimeString(); 
            $gymAs->save();

            Controller::NewRegisterTrigger("An insertion was made in the Gym assistances table '$gymAs->gym_ass_id'", 3, $request->use_id);
            
            return response()->json([
                'status' => True,
                'message' => "The Gym assistances has been created successfully.",
            ], 200);
        }
    } else {
        return response()->json([
            'status' => False,
            'message' => 'Access denied. This action can only be performed by active administrators.'
        ], 403); 
    }
}

    

    public function show($id)
{
    $gymAs = Gym_assistance::find($id);


    if (empty($gymAs)) {
        return response()->json([
            'status' => false,
            'message' => 'The requested Gym assistances was not found.'
        ], 404);
    }else{
       
        
        return response()->json([
        'status' => true,
        'data' => $gymAs
    ]);}



    
}


public function update(Request $request, $id)
{



    // $gymAss = Gym_assistance::find($id);

    // if ($request->acc_administrator == 1) {
    //     $gymAss = Gym_assistance::find($id);
    //     if ($gymAss == null) {
    //         return response()->json([
    //             'status' => false,
    //             'data' => ['message' => 'The searched Gym assistances was not found']
    //         ], 400);
    //     } else {
    //         $rules = [

    //             'per_id' =>'required|exists:persons|numeric'
    //         ];

    //         $validator = Validator::make($request->input(), $rules);
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => $validator->errors()->all()
    //             ]);
    //         } else {
    //             $gymAss->gym_ass_date = now()->toDateString(); 
    //             $gymAss->per_id = $request->per_id;
    //             $gymAss->save();
    //             Controller::NewRegisterTrigger("An update was made in the Gym assistances table", 4, $request->use_id);

    //             return response()->json([
    //                 'status' => true,
    //                 'message' => "The Gym assistances has been updated."
    //             ], 200);
    //         }
    //     }
    // } else {
    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Access denied. This action can only be performed by active administrators.'
    //     ], 403);
    // }
    return response()->json([
        'status' => false,
        'message' => 'function not available'
    ]);
}
public function FiltredDate($date)
{
    try {
        $gymAss = Gym_assistance::selectByDate($date);
        
        
        return response()->json([
            'status' => true,
            'data' => $gymAss
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
    
}
public function FiltredDateRange(Request $request)
{
        $rules = [
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $gymAss = Gym_assistance::selectByDateRange($startDate,$endDate);

        return response()->json([
            'status' => true,
            'data' => $gymAss
        ], 200);
    
    
}

    public function destroy(Request $request,$id)
    {
        
                return response()->json([
                    'status' => false,
                    'message' => 'function not available'
                ]);
    }
}

