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
       
        $solicitudes = solicitudes::getbienestar_news();
 
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
                   $solicitud = new solicitudes($request->input());
                   $solicitud->save();
                   Controller::NewRegisterTrigger("An insertion was made in the solicitudes table'$solicitud->sol_id'", 3,$use_id);
                   $id = $solicitud->sol_id;
                   $bienestar_news=SolicitudesController::Getbienestar_news($id);
                   return response()->json([
                    'status' => True,
                    'message' => "The request has been created successfully.",
                    'data' => $bienestar_news

                   ], 200);
            }
        } else {
            return response()->json([
 
                'status' => false,
                'message' => 'Access denied. This action can only be performed by active administrators.'
            ], 403);
        }
   
}
public function Getbienestar_news($id)
{
    $sol_id = $id;
    $bienestar_news = DB::table('bienestar_news')
        ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
        ->select('bie_new_date', 'persons.per_name')
        ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the solicitudes table\'$sol_id\''")
        ->get();

    if ($bienestar_news->count() > 0) {
        return $bienestar_news[0];
    } else {
        return null;
    }
}
    public function show($proj_id,$use_id,$id)
    {
       
        $solicitudes =  solicitudes::find($id);
        $bienestar_news=SolicitudesController::Getbienestar_news($id);

        if ($solicitudes == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched request was not found']
            ], 400);
        } else {
            Controller::NewRegisterTrigger("A search was performed in the solicitudes table", 4,$use_id);
            $solicitudes->new_date = $bienestar_news->bie_new_date;
            $solicitudes->createdBy = $bienestar_news->per_name;
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
        $newSol=($solicitudes->sol_status == 1) ?0:1;
                $solicitudes->sol_status = $newSol;
                $solicitudes->save();
                Controller::NewRegisterTrigger("An change status was made in the solicitudes table",2,$proj_id, $use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested solicitudes has been change status successfully'
                ]);
            
    }
    public function filtredforSolicitudes($proj_id,$use_id,$column,$data,Request $request)
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
}