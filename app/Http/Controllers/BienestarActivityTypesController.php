<?php

namespace App\Http\Controllers;

use App\Models\BienestarActivityTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BienestarActivityTypesController extends Controller
{
    public function index()
    {
        $bienestarActTypes = BienestarActivityTypes::all();
        Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities Types table",4,2,1);

        return response()->json([
            'status' => true,
            'data' => $bienestarActTypes
        ],200);
    }
    public function store(Request $request)
    {
        $rules = [
            'bie_act_typ_name' =>'required|string|min:1|max:55|regex:/^[A-Z\s]+$/',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $bienestarActTypes = new BienestarActivityTypes($request->input());
            $bienestarActTypes->save();
            Controller::NewRegisterTrigger("An insertion was made in the Bienestar Activities types table",4,2,1);

            return response()->json([
                'status' => true,
                'message' => "The bienestar activity type '".$bienestarActTypes->bie_act_typ_name."' has been created successfully."
            ],200);
        }

    }
    public function show($id)
    {
        $bienestarActTypes = BienestarActivityTypes::find($id);
        if ($bienestarActTypes == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested bienestar activity type was not found']
            ],400);
        } else {
            Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities types table",4,2,1);

            return response()->json([
                'status' => true,
                'data' => $bienestarActTypes
            ]);
        }

    }
    public function update(Request $request, $id)
    {
        $bienestarActTypes = BienestarActivityTypes::find($id);
        if ($bienestarActTypes == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested bienestar activity type was not found']
            ],400);
        } else {
            $rules = [
                'bie_act_typ_name' =>'required|string|min:1|max:55|regex:/^[A-Z\s]+$/',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $bienestarActTypes->bie_act_typ_name = $request->bie_act_typ_name;
                $bienestarActTypes->save();
                Controller::NewRegisterTrigger("An update was made in the Bienestar Activities types table",1,2,1);

                return response()->json([
                   'status' => True,
                    'data' => "The bienestar activity type ".$bienestarActTypes->bie_act_typ_name." has been updated successfully."
                ],200);
            };
        }

    }
    public function destroy(BienestarActivityTypes $bienestarActTypes)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available"
        ],400);

    }
}
