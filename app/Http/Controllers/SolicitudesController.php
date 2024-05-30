<?php
 
namespace App\Http\Controllers;
 
use App\Models\solicitudes;

use Facade\IgnitionContracts\Solution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
 
class SolicitudesController extends Controller
{
    public function index()
{
    $solicitudes = Solicitudes::select();
    return response()->json([
        'status' => true,
        'data' => [
            'message' => $solicitudes,
        ]
    ], 200);
}

public function store(Request $request)
{
    if ($request->acc_administrator == 1) {
        $rules = [
            'sol_date' => 'date',
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
            $currentDate = now()->toDateString();
            $request->merge(['sol_date' => $currentDate]);
            $solicitud = new solicitudes($request->input());
            $solicitud->sol_status = 0;
            $solicitud->save();
            Controller::NewRegisterTrigger("An insertion was made in the solicitudes table '$solicitud->sol_id'", 3, $request->use_id);

            return response()->json([
                'status' => True,
                'message' => "The request has been created successfully.",
            ], 200);
        }
    } else {
        $student = \DB::table('viewStudents')->where('stu_id', $request->use_id)->first();

        if ($student) {
            $rules = [
                'sol_date' => 'date',
                'rea_typ_id' => 'required|integer',
                'sol_typ_id' => 'required|integer',
                'stu_id' => 'required|integer'
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
                $solicitud = new solicitudes($request->input());
                $solicitud->sol_status = 0;
                $solicitud->save();
                Controller::NewRegisterTrigger("An insertion was made in the solicitudes table '$solicitud->sol_id'", 3, $request->use_id);

                return response()->json([
                    'status' => True,
                    'message' => "The request has been created successfully.",
                ], 200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. You must be an active student to create a request.'
            ], 403);
        }
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
                    'sol_status'=> 'required|string|in:0,1,2,3',
                    'rea_typ_id' =>'required|exists:reason_types|integer',
                    'sol_typ_id' =>'required|exists:solicitude_types|integer',
                    'stu_id' =>'required|exists:students|integer'
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
                    $solicitudes->sol_status = $request->sol_status;
                    $solicitudes->rea_typ_id = $request->rea_typ_id;
                    $solicitudes->sol_typ_id = $request->sol_typ_id;
                    $solicitudes->stu_id = $request->stu_id;
                    $solicitudes->save();
                    Controller::NewRegisterTrigger("An update was made in the solicitudes table", 4,$request->use_id);
 
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
    public function destroy(Request $request,$id)
    {
        $solicitudes = solicitudes::find($id);
        $newSol=($solicitudes->sol_status == 0) ? 3 : 0;
                $solicitudes->sol_status = $newSol;
                $solicitudes->save();
                Controller::NewRegisterTrigger("An change status was made in the solicitudes table",2,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested solicitudes has been change status successfully'
                ]);
            
    }
    public function filtredforSolicitudes($column,$data,Request $request)
    {
        try {
            
            $solicitudes = ($column == 'sol_date') ? DB::table('ViewSolicitudes')->OrderBy($column, 'DESC')->where($column, 'like', '%'.$data.'%')->take(100)->get() : DB::table('ViewSolicitudes')->OrderBy($column, 'DESC')->where($column, '=', $data)->take(100)->get();
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
    public function filtredPesolicitud($id)
{
    try {
        $solicitudes = solicitudes::findBysol($id);
        
        
        return response()->json([
            'status' => true,
            'data' => $solicitudes
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
}public function filtreduser($id, $rea_typ_type = null)
{
    try {
        if ($rea_typ_type !== null) {
            $solicitudes = solicitudes::findByUse($id, $rea_typ_type);
        } else {
            $solicitudes = solicitudes::findByUse($id);
        }
        
        return response()->json([
            'status' => true,
            'data' => $solicitudes
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
}

    
}