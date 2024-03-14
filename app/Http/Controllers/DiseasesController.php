<?php
namespace App\Http\Controllers;
use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class DiseasesController extends Controller
{
    public function index($proj_id, $use_id)
    {
        try {
            $disease = Disease::all();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Disease", 4, $proj_id, $use_id);
            return response()->json([
                'status' => true,
                'data' => $disease,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "Error in index, not found elements"
            ], 500);
        }
    }
    public function store($proj_id, $use_id, Request $request)
    {
        $rules = [
            'dis_name' => 'required|string|min:1|max:50|unique:Disease|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
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
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Disease: $request->dis_name", 3, $proj_id, $use_id);
            return response()->json([
                'status' => true,
                'message' => "The Disease: " . $disease->dis_name . " has been created."
            ], 200);
        }
    }
    public function show($proj_id, $use_id, $id)
    {
        $disease = Disease::find($id);
        if ($disease == null) {
            return response()->json([
                'status' => False,
                'data' => ['message' => 'The disease requested not found'],
            ], 400);
        } else {
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Doctypes por dato especifico: $id", 4, $proj_id, $use_id);
            return response()->json([
                'status' => true,
                'data' => $disease
            ]);
        }
    }
    public function update($proj_id, $use_id, Request $request, $id)
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
            $validate = Controller::validate_exists($request->dis_name, 'Disease', 'dis_name', 'dis_id', $id);
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
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Disease del dato: id->$id", 1, $proj_id, $use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The Disease: " . $disease->dis_name . " has been update."
                ], 200);
            }
        }
    }
    public function destroy(Disease $Disease)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
        ], 400);
    }
}