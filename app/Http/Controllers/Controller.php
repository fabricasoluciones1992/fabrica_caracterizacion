<?php

namespace App\Http\Controllers;

use App\Models\Reports;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Facade\FlareClient\Report;
use Illuminate\Http\Request;

//login
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function NewRegisterTrigger($bie_new_description,$new_typ_id, $use_id)
    {
        DB::statement("CALL bie_new_register('" . addslashes($bie_new_description) . "', $new_typ_id, $use_id)");
    }

//filtro rango de fechas


    public function student($id,$docTypeId) {
        $students = DB::select("SELECT * FROM viewStudents WHERE per_document = '$id' AND doc_typ_id = $docTypeId");
        return $students;
    }
    public function login(Request $request){
        $response = Http::post('10.10.1.123/fabrica_general/public/index.php/api/login/2', [
            "use_mail" => $request->use_mail,
            "use_password" => $request->use_password,
        ]);
        

        if ($response->successful()) {
            $responseData = $response->json();
            $token = isset($responseData['token']) ? $responseData['token'] : null;
            if ($token !== null) {
                $user=DB::table('users')->where("use_mail",'=',$request->use_mail)->first();
                $user = User::find($user->use_id);
                Auth::login($user);

                return response()->json([
                    'status' => true,
                    'data' => [
                        "token" => $token,
                        "use_id" => $user->use_id,
                        "acc_administrator" => $responseData['acc_administrator'],
                        'per_document' => $responseData['per_document'],

                        ]
                ],200);
            } else {
                // Handle the case where 'token' is not present in the response
                return response()->json([
                    'status' => false,
                    'message' => $response->json()
                ],401);
            }
        } else {
            // Handle the case where the HTTP request was not successful
            return response()->json([
                'status' => false,
                'message' => $response->json()['message']
            ],400);
        }
    }

    public static function lastEnrollments($stu_id){
        $data = DB::table('viewEnrollments')
            ->where('stu_id', $stu_id)
            ->orderBy('stu_enr_id', 'desc')
            ->first();
        return $data;
    }
    public function lastScholarships($stu_id){
        $data = DB::table('viewStudents')
            ->leftJoin('history_scholarships', 'viewStudents.stu_id', '=', 'history_scholarships.stu_id')
            ->leftJoin('scholarships', 'history_scholarships.sch_id', '=', 'scholarships.sch_id')
            ->select(
                'viewStudents.*',
                'history_scholarships.his_sch_id',
                'history_scholarships.sch_id',
                'scholarships.sch_name',
                'scholarships.sch_description'
            )
            ->where('viewStudents.stu_id', $stu_id)
            ->get();

        return $data;
    }



    public function viewStudentSol($code) {
        $codigoStu = DB::select("SELECT * FROM viewSolicitudes
        WHERE per_document = $code
        ");
        if ($codigoStu == null) {
            return response()->json([
               'status' => false,
                "data" => ['message' => 'The searched student was not found']
            ],400);
        } else {
            return response()->json([
                'status' => true,
                'data' => $codigoStu
            ],200);
        }
    }


    public function viewStudentBie($code) {
        $codigoStu = DB::select("SELECT * FROM viewAssitances
        WHERE per_document = $code
        ");
        if ($codigoStu == null) {
            return response()->json([
               'status' => false,
                "data" => ['message' => 'The searched student was not found']
            ],400);
        } else {
            return response()->json([
                'status' => true,
                'data' => $codigoStu
            ],200);
        }
    }


    public function filtredforDocument($id, $docTypeId)
{
    try {
        $persons =  DB::select("SELECT * FROM ViewPersons WHERE per_document = '$id' AND doc_typ_id = $docTypeId");

        return response()->json([
            'status' => true,
            'data' => $persons
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
}

public function filtredAssistance($id, $docTypeId)
{
    try {
        $person =  DB::select("SELECT * FROM ViewPersons WHERE per_document = '$id' AND doc_typ_id = $docTypeId");

        $inscription = DB::table('gym_inscriptions')->where('gym_ins_status', '=', '1')->where('per_id', '=', $person[0]->per_id)->get();
        if ($inscription != '[]') {
            return response()->json([
                'status' => true,
                'data' => $person
            ], 200);
        }else{
            return response()->json([
                'status' => true,
                'data' => 'User is not enrolled in the gym. Cannot register assistance.'
            ], 200);
        }
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
}

public function reports(Request $request){
    $data = Reports::index($request);
    if ($data == '[]') {
        return response()->json([
            'status' => false,
            'data' => "No data found"
        ]);
    }else{
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

}
public function reportsIndi(Request $request){
    $data = Reports::select($request);
    if ($data == '[]') {
        return response()->json([
            'status' => false,
            'data' => "No data found"
        ]);
    }else{
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
public function reportStudent(Request $request){
    $data = Reports::reportStudent($request);
    return response()->json([
        'status' => true,
        'data' => $data
    ]);
}
public function docsTypesId(){

    $data = DB::table('document_types')->get();
    if($data == '[]'){
        return response()->json([
           'status' => false,
            'data' => "No data found"
        ]);
    }else{
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }


}
public function viewEmployees(){
    $employees = DB::table('viewEmployees')->get();
    if($employees == '[]'){
        return response()->json([
           'status' => false,
            'data' => "No data found"
        ]);
    }else{
        return response()->json([
            'status' => true,
            'data' => $employees
        ]);
    }
}
public function viewCareers(){
    $careers = DB::table('careers')->get();
    if($careers == '[]'){
        return response()->json([
           'status' => false,
            'data' => "No data found"
        ]);
    }else{
        return response()->json([
            'status' => true,
            'data' => $careers
        ]);
    }
}
public function viewTypesStudent(){
    $students = DB::table('students_types')->get();
    if($students == '[]'){
        return response()->json([
           'status' => false,
            'data' => "No data found"
        ]);
    }else{
        return response()->json([
            'status' => true,
            'data' => $students
        ]);
    }
}
public function validate_exists($data, $table, $column, $PK, $pk){
    $values = DB::table($table)->get([$PK, $column]);
    foreach ($values as $value) {
        if ($value->$column == $data && $value->$PK != $pk) {
            return 0;
        }
    }
    return 1;
}
}

