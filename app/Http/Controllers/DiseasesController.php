<?php
namespace App\Http\Controllers;
use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
class DiseasesController extends Controller
{
    public function index()
    {
        $diseases = Disease::all();
        return response()->json([
                'status' => true,
                'data' => $diseases
            ], 200);
        
    }
    public function store( Request $request)
    {
        $rules = [

            'dis_name' => 'required|string|min:1|max:50|unique:diseases|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $disease = new Disease(($request->input()));
            $disease->save();
            Controller::NewRegisterTrigger("An insertion was made in the Diseases table'$disease->dis_id'", 3, $request->use_id);
            
            return response()->json([
                'status' => true,
                'message' => "The Disease: " . $disease->dis_name . " has been created.",

            ], 200);
        }
    }

    public function show($id)
    {
        $disease = Disease::find($id);

        if ($disease == null) {
            return response()->json([
                'status' => False,
                'data' => ['message' => 'The disease requested not found'],
            ], 400);
        } else {
            
            return response()->json([
                'status' => true,
                'data' => $disease
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $disease = Disease::find($id);
        if ($disease == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The disease requested not found'],
            ], 400);
        } else {
            $rules = [

                'dis_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->dis_name, 'diseases', 'dis_name', 'dis_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                    'status' => False,
                    'message' => $msg
                ]);
            } else {
                $disease = Disease::find($id);
                $disease->dis_name = $request->dis_name;
                $disease->save();
                Controller::NewRegisterTrigger("An update was made in the diseases table: id->$id", 4, $request->use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The Disease: " . $disease->dis_name . " has been update."
                ], 200);
            }
        }
    }
    public function destroy(Request $request,$id)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
        ], 400);
    }
}