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

    


    public function student($id) {
        $students = DB::select("SELECT * FROM viewStudents WHERE per_document = $id");
        return $students;
    }
    public function login(Request $request){
        $response = Http::post('http://127.0.0.1:8088/api/login/2', [
            "use_mail" => $request->use_mail,
            "use_password" => $request->use_password,
        ]);
        $user=DB::table('users')->where("use_mail",'=',$request->use_mail)->first();
        $user = User::find($user->use_id);
        Auth::login($user);

        if ($response->successful()) {
            $responseData = $response->json();
            $token = isset($responseData['token']) ? $responseData['token'] : null;
            if ($token !== null) {


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
                ]);
            }
        } else {
            // Handle the case where the HTTP request was not successful
            return response()->json([
                'status' => false,
                'message' => $response->json()['message']
            ]);
        }
    }

    // public function viewStudentMed($code) {
    //     $codigoStu = DB::select("SELECT * FROM Vista_Historial_Medico_Estudiante
    //     WHERE stu_id = $code
    //     ");
    //     if ($codigoStu == null) {
    //         return response()->json([
    //            'status' => false,
    //             "data" => ['message' => 'The searched student was not found']
    //         ],400);
    //     } else {
    //         return response()->json([
    //             'status' => true,
    //             'data' => $codigoStu
    //         ],200);
    //     }
    // }

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
        $codigoStu = DB::select("SELECT * FROM viewAssistances
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
    public static function findByDocument($id, $docTypeId){
        $persons = DB::select("SELECT * FROM ViewPersons WHERE per_document = $id AND doc_typ_id = $docTypeId");
        return $persons;
    }

    public function filtredforDocument($id, $docTypeId)
{
    try {
        $persons = Controller::findByDocument($id, $docTypeId);

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
    $employees = DB::table('viewemployees')->get();
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
    $employees = DB::table('careers')->get();
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
public function viewTypesStudent(){
    $employees = DB::table('students_types')->get();
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
}
