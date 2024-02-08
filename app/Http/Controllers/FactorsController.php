<?php

namespace App\Http\Controllers;

use App\Models\factor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FactorsController extends Controller
{
    public function index()
    {
        $token = Controller::auth();
        // $data = Controller::genders($token);
        $factors = factor::all();
        Controller::NewRegisterTrigger("A search was performed in the factors table", 4, env('APP_ID'), $token['use_id']);
        return response()->json([
            'status' => true,
            'data' => $factors,
        ], 200);

    }
    public function store(Request $request)
    {
        session_start();
        if ($_SESSION['acc_administrator'] == 1) {
            $rules = [
                'fac_name' => 'required|string|min:1|max:50|regex:/^[A-Z\s]+$/'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([

                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            } else {
                $factor = new factor($request->input());
                $factor->save();
                Controller::NewRegisterTrigger("An insertion was made in the factors table", 3, 2, 1);

                return response()->json([
                'status' => True,
                'message' => "The factor type '".$factor->fac_name."' has been created successfully."
                ], 200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. This action can only be performed by active administrators.'
            ], 403); 
        }
    }
    public function show($id)
    {
        $factor = factor::find($id);
        if ($factor == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested factor was not found']
            ], 400);
        } else {
            Controller::NewRegisterTrigger("A search was performed in the factors table", 4, 2, 1);

            return response()->json([
                'status' => true,
                'data' => $factor
            ]);
        }

    }
    public function update(Request $request, $id)
    {
        session_start();
        if ($_SESSION['acc_administrator'] == 1) {
            $factor = factor::find($id);
            if ($factor == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested factor was not found']
                ], 400);
            } else {
                $rules = [
                    'fac_name' => 'required|string|min:1|max:50|regex:/^[A-Z\s]+$/'

                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                    ]);
                } else {
                    $factor->fac_name = $request->fac_name;
                    $factor->save();
                    Controller::NewRegisterTrigger("An update was made in the factors table", 1, 2, 1);

                    return response()->json([
                'status' => True,
                    'data' => "The factor '".$factor->fac_name."' has been updated successfully."
                    ], 200);
                };
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. This action can only be performed by active administrators.'
            ], 403); 
        }
    }
    public function destroy(factor $factors)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available"

        ], 400);

    }
    
}
