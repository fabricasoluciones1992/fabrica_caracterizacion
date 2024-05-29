<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Allergie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AllergiesController extends Controller
{
    public function index()
    {

        $allergies = Allergie::all();
    
        return response()->json([
                'status' => true,
                'data' => $allergies
         ],200);
        
    }
    public function store(Request $request)
    {
        
            if ($request->acc_administrator == 1) {
                $rules = [

                    'all_name' => 'required|string|min:1|max:50|exists:allergies|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                    
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $allergie = new Allergie($request->input());
                    $allergie->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Allergies table'$allergie->all_id'",3,$request->use_id);
                    // $id = $allergie->all_id;
                    // $bienestar_news=AllergiesController::Getbienestar_news($id);

                    return response()->json([
                        'status' => True,
                        'message' => "The Allergie type ".$allergie->all_name." has been successfully created."

                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This Allergie can only be performed by active administrators.'
                ], 403); 
            }
        
    }
//     public function Getbienestar_news($id)
// {
//     $all_id = $id;
//     $bienestar_news = DB::table('bienestar_news')
//         ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//         ->select('bie_new_date', 'persons.per_name')
//         ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the Allergies table\'$all_id\''")
//         ->get();

//     if ($bienestar_news->count() > 0) {
//         return $bienestar_news[0];
//     } else {
//         return null;
//     }
// }
    public function show($id)
    {

        $allergie = Allergie::find($id);
        
        // $bienestar_news=AllergiesController::Getbienestar_news($id);

            if ($allergie == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested Allergie was not found']
                ],400);
            }else{

                // $allergie->new_date = $bienestar_news->bie_new_date;
                // $allergie->per_name = $bienestar_news->per_name;
                return response()->json([
                    'status' => true,
                    'data' => $allergie
                ]);
            }
        
    }
    public function update(Request $request, $id)
    {

        $allergie = Allergie::find($id);
        if ($request->acc_administrator == 1) {
                $rules = [

                    'all_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $allergie->all_name = $request->all_name;
                    $allergie->save();
                    Controller::NewRegisterTrigger("An update was made in the Allergies table",4,$request->use_id);

                    return response()->json([
                        'status' => True,
                        'data' => "The Allergie ".$allergie->all_name." has been successfully updated."
                    ],200);
                }
     }else {
        return response()->json([
            'status' => false,
            'message' => 'Access denied. This Allergie can only be performed by active administrators.'
        ], 403); 
    }
    }
        
    
    public function destroy(Request $request, $id)
    {

        $allergie = Allergie::find($id);
        
            
                return response()->json([
                    'status' => false,
                    'message' => 'Function not available.'
                ]);
             
    }
}