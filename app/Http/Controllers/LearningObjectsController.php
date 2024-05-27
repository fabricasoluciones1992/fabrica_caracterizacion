<?php

namespace App\Http\Controllers;

use App\Models\LearningObjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LearningObjectsController extends Controller
{

    public function index()
    {
        $learningObjects = LearningObjects::select();
        if($learningObjects == null){
            return response()->json([
            'status' => False,
            'message' => 'There are no learning objects available.'
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizó una busqueda de datos en la tabla Learning objects ",3,2,1);
            return response()->json([
                'status'=>True,
                'data'=>$learningObjects],200);
        }

    }




    public function store(Request $request)
    {


            $rules =[
                'lea_obj_object' => ['required', 'regex:/^[A-ZÁÉÍÓÚÜÀÈÌÒÙÑ\s]+$/'],
                'cor_mat_id' => ['required']
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ],400);
            }else{
                $learningObject = new LearningObjects($request->input());
                $learningObject->lea_obj_object = $request->lea_obj_object;
                $learningObject->cor_mat_id = $request->cor_mat_id ;
                $learningObject ->save();
                Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla Learning objects ",3,2,1);
                return response()->json([
                    'status' => True,
                    'message' => 'Learning object: '.$learningObject->lea_obj_object.' created successfully.',
                    'data' => $learningObject
                ],200);
            }
    }


    public function show($id)
    {
        $learningObjects = LearningObjects::findOne($id);
        if($learningObjects == null){
            return response()->json([
            'status' => False,
            'message' => 'There are no learning objects available.'
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizó una busqueda de datos en la tabla Learning objects ",3,2,1);
            return response()->json([
                'status'=>True,
                'data'=>$learningObjects],200);
        }
    }


    public function update(Request $request, $id)
    {
        $learningObject = LearningObjects::find($id);
        if($learningObject == null){
            return response()->json([
            'status' => False,
            'message' => 'There are no learning objects available.'
            ],400);
               }
         $rules =[
            'lea_obj_object' => ['required', 'regex:/^[A-ZÁÉÍÓÚÜÀÈÌÒÙÑ\s]+$/'],
            'cor_mat_id' => ['required']
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ],400);
        }else{
            $learningObject->lea_obj_object = $request->lea_obj_object;
            $learningObject->cor_mat_id = $request->cor_mat_id ;
            $learningObject ->save();
            Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla Learning objects ",3,2,1);
            return response()->json([
                'status' => True,
                'message' => 'Learning object: '.$learningObject->lea_obj_object.' updated successfully.',
                'data' => $learningObject
            ],200);
        }
    }


    public function destroy()
    {
        Controller::NewRegisterTrigger("Se intentó eliminar un dato en la tabla Learning objects ",3,2,1);
        return response()->json([
            'message' => 'This function is not allowed.'
        ],400);
    }
}
