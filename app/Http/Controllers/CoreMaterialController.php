<?php

namespace App\Http\Controllers;

use App\Models\CoreMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoreMaterialController extends Controller
{

    public function index()
    {
        $coreMaterial = CoreMaterial::select();
        if($coreMaterial == null){
            return response()->json([
            'status' => False,
            'message' => 'There are no core materials available.'
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizó una busqueda de datos en la tabla CoreMaterial ",3,2,1);
            return response()->json([
                'status'=>True,
                'data'=>$coreMaterial],200);
        }

    }


    public function store(Request $request)
    {
        $rules =[
            'cor_mat_name' => ['required', 'regex:/^[A-ZÁÉÍÓÚÜÀÈÌÒÙÑ\s]+$/'],
            'cor_mat_semester' => ['required', 'regex:/^[1-7]$/'],
            'car_id' => ['required']
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ],400);
        }else{
            $coreMaterial = new CoreMaterial($request->input());
            $coreMaterial->cor_mat_name = $request->cor_mat_name;
            $coreMaterial->cor_mat_semester = $request->cor_mat_semester ;
            $coreMaterial->car_id = $request->car_id ;
            $coreMaterial ->save();
            Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla CoreMaterial ",3,2,1);
            return response()->json([
                'status' => True,
                'message' => 'Core material: '.$coreMaterial->cor_mat_name.' created successfully.',
                'data' => $coreMaterial
            ],200);
        }

    }


    public function show($id)
    {
        $coreMaterial = CoreMaterial::findOne($id);
        if($coreMaterial == null){
            return response()->json([
            'status' => False,
            'message' => 'There are no core materials available.'
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizó una busqueda de datos en la tabla CoreMaterial ",3,2,1);
            return response()->json([
                'status'=>True,
                'data'=>$coreMaterial],200);
        }
    }



    public function update(Request $request, $id)
    {
        $coreMaterial = CoreMaterial::find($id);
        if($coreMaterial == null){
            return response()->json([
            'status' => False,
            'message' => 'There are no core materials available.'
            ],400);
        }
        $rules =[
            'cor_mat_name' => ['required', 'regex:/^[A-ZÁÉÍÓÚÜÀÈÌÒÙÑ\s]+$/'],
            'cor_mat_semester' => ['required', 'regex:/^[1-7]$/'],
            'car_id' => ['required']
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ],400);
        }else{
            $coreMaterial->cor_mat_name = $request->cor_mat_name;
            $coreMaterial->cor_mat_semester = $request->cor_mat_semester ;
            $coreMaterial->car_id = $request->car_id ;
            $coreMaterial ->save();
            Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla CoreMaterial ",3,2,1);
            return response()->json([
                'status' => True,
                'message' => 'Core material: '.$coreMaterial->cor_mat_name.' updated successfully.',
                'data' => $coreMaterial
            ],200);
        }
    }


    public function destroy()
    {
        Controller::NewRegisterTrigger("Se intentó eliminar un dato en la tabla CoreMaterial ",3,2,1);
        return response()->json([
            'message' => 'This function is not allowed.'
        ],400);
    }
}
