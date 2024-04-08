<?php
 
namespace App\Http\Controllers;
 
use App\Models\solicitudes;
use Facade\IgnitionContracts\Solution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
 
class SolicitudesController extends Controller
{
    public function index($proj_id,$use_id)
    {
       
        $solicitudes = solicitudes::select();
 
        return response()->json([
           'status' => true,
            'data' => $solicitudes
        ], 200);
 
   
}
    public function store($proj_id,$use_id,Request $request)
    {
       
        if ($request->acc_administrator == 1) {
            $rules = [
                'sol_date' =>'date',
                'sol_responsible'=>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                'sol_status'=>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                'rea_id' =>'required|integer',
                'fac_id' =>'required|integer',
                'sol_typ_id' =>'required|integer',
                'stu_id' =>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                   ]);
               }else{
                   $currentDate = now()->toDateString();
       
                   $request->merge(['sol_date' => $currentDate]);
                   $solicitudes = new solicitudes($request->input());
                   $solicitudes->save();
                   Controller::NewRegisterTrigger("An insertion was made in the solicitudes table", 3,  $proj_id, $use_id);
       
                   return response()->json([
                    'status' => True,
                    'message' => "The request has been created successfully."
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
       
        $solicitudes =  solicitudes::find($id);

        if ($solicitudes == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched request was not found']
            ], 400);
        } else {
            Controller::NewRegisterTrigger("A search was performed in the solicitudes table", 4,  $proj_id, $use_id);
 
            return response()->json([
               'status' => true,
                'data' => $solicitudes
            ]);
        }
 
   
}
    public function update($proj_id,$use_id,Request $request, $id)
    {
       
        $solicitudes = solicitudes::find($id);
 
        if ($request->acc_administrator == 1) {
            $solicitudes = solicitudes::find($id);
            if ($solicitudes == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The searched request was not found']
                ], 400);
            } else {
                $rules = [
                    'sol_date' =>'date',
                    'sol_responsible'=>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                    'sol_status'=>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                    'rea_id' =>'required|integer',
                    'fac_id' =>'required|integer',
                    'sol_typ_id' =>'required|integer',
                    'stu_id' =>'required|integer'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {
                    $currentDate = now()->toDateString();
 
                    $request->merge(['sol_date' => $currentDate]);
 
                    $solicitudes->sol_date = $request->sol_date;
                    $solicitudes->sol_responsible = $request->sol_responsible;
                    $solicitudes->sol_status = $request->sol_status;
                    $solicitudes->rea_id = $request->rea_id;
                    $solicitudes->fac_id = $request->fac_id;
                    $solicitudes->sol_typ_id = $request->sol_typ_id;
                    $solicitudes->stu_id = $request->stu_id;
                    $solicitudes->save();
                    Controller::NewRegisterTrigger("An update was made in the solicitudes table", 4,$use_id);
 
                    return response()->json([
                    'status' => True,
                    'message' => "The request has been updated successfully."
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
        $solicitudes = solicitudes::find($id);
        
            if ($solicitudes->sol_status == 1){
                $solicitudes->sol_status = 0;
                $solicitudes->save();
                Controller::NewRegisterTrigger("An delete was made in the solicitudes table",2,$proj_id, $use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested solicitudes has been disabled successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The requested solicitudes has already been disabled previously'
                ]);
            }
    }
    public function filtredforSolicitudes($proj_id,$use_id,$column,$data,Request $request)
    {
        try {
            
            $solicitudes = ($column == 'sol_date') ? DB::table('ViewSolicitudes')->OrderBy($column, 'DESC')->where($column, 'like', '%'.$data.'%')->take(100)->get() : DB::table('ViewSolicitudes')->OrderBy($column, 'DESC')->where($column, '=', $data)->take(100)->get();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla solicitudes",1,$proj_id,$use_id);
            return response()->json([
               'status' => true,
                'data' => $solicitudes
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => "Error occurred while found elements"
            ],500);
        }
    }
}