<?php
 
namespace App\Http\Controllers;
 
use App\Models\Solicitudes;

use Facade\IgnitionContracts\Solution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
 
class SolicitudesController extends Controller
{
    public function index()
{
    $Solicitudes = Solicitudes::select();
    return response()->json([
        'status' => true,
        'data' => [
            'message' => $Solicitudes,
        ]
    ], 200);
}

public function store(Request $request)
{
    
        $rules = [
            'rea_typ_id' => 'required|exists:reason_types|integer',
            'sol_typ_id' => 'required|exists:solicitude_types|integer',
            'stu_id' => 'required|exists:students|integer',

        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
            
        } else {
            $existingActivity = Solicitudes::where('rea_typ_id', $request->rea_typ_id)
            ->where('sol_typ_id', $request->sol_typ_id)
            ->where('stu_id', $request->stu_id)
            ->first();

        if ($existingActivity) {
            return response()->json([
                'status' => false,
                'message' => 'Has already been taken.'
            ]);
        }
            $solicitud = new Solicitudes($request->input());
            $solicitud->sol_date = now()->toDateString(); 
            $solicitud->sol_status = 0;
            $solicitud->save();
            Controller::NewRegisterTrigger("An insertion was made in the Solicitudes table '$solicitud->sol_id'", 3, $request->use_id);

            return response()->json([
                'status' => True,
                'message' => "The request has been created successfully.",
            ], 200);
        }
    
        
    
}





public function show($id)
{
    $solicitud = Solicitudes::search($id);

    if (!$solicitud) {
        return response()->json([
            'status' => false,
            'message' => 'The requested solicitude does not exist.'
        ], 404);
    }
    return response()->json([
        'status' => true,
        'data' => $solicitud
    ], 200);
}

    public function update(Request $request, $id)
    { 
        if ($request->acc_administrator == 1) {
            $solicitude = Solicitudes::find($id);
            if ($solicitude == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The searched request was not found.']
                ], 400);
            } else {
                $rules = [

                    'sol_status'=> 'required|integer',
                    'rea_typ_id' =>'required|integer',
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
                    $solicitude->sol_date = now()->toDateString(); 
                    $solicitude->sol_status = $request->sol_status;
                    $solicitude->rea_typ_id = $request->rea_typ_id;
                    $solicitude->sol_typ_id = $request->sol_typ_id;
                    $solicitude->stu_id = $request->stu_id;
                    $solicitude->save();
                    Controller::NewRegisterTrigger("An update was made in the Solicitudes table",4,$request->use_id);
 
                    return response()->json([
                    'status' => True,
                    'message' => "The request has been updated successfully.",
                    ], 200);
                }
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. This Action can only be performed by active administrators.'
            ], 403);
        }
   
}
    public function destroy(Request $request,$id)
    {
        $Solicitudes = Solicitudes::find($id);
        $newSol=($Solicitudes->sol_status == 0) ? 3 : 0;
                $Solicitudes->sol_status = $newSol;
                $Solicitudes->save();
                Controller::NewRegisterTrigger("An change status was made in the Solicitudes table",2,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested Solicitudes has been change status successfully'
                ]);
            
    }
    public function filtredforSolicitudes($column,$data,Request $request)
    {
        try {
            
            $Solicitudes = ($column == 'sol_date') ? DB::table('ViewSolicitudes')->OrderBy($column, 'DESC')->where($column, 'like', '%'.$data.'%')->take(100)->get() : DB::table('ViewSolicitudes')->OrderBy($column, 'DESC')->where($column, '=', $data)->take(100)->get();
            return response()->json([
               'status' => true,
                'data' => $Solicitudes
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => "Error occurred while found elements"
            ],500);
        }
    }
public function filtredPesolicitud($id)
{
    try {
        $Solicitudes = Solicitudes::findBysol($id);
        
        
        return response()->json([
            'status' => true,
            'data' => $Solicitudes
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
    
}
public function filtredStatusSol($id)
{
    try {
        $Solicitudes = Solicitudes::findBystatus($id);
        
        
        return response()->json([
            'status' => true,
            'data' => $Solicitudes
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
    
}
public function filtreduser($id, $rea_typ_type = null)
{
    try {
        if ($rea_typ_type !== null) {
            $Solicitudes = Solicitudes::findByUse($id, $rea_typ_type);
        } else {
            $Solicitudes = Solicitudes::findByUse($id);
        }
        
        return response()->json([
            'status' => true,
            'data' => $Solicitudes
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
}

    
}