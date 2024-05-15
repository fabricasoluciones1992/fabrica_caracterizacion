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

    public function students($proj_id,$use_id,Request $request) {
        $token = $request->header('Authorization');

        if ($token == "Token not found in session") {
            return $token;
        } else {

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get('http://127.0.0.1:8088/api/persons/' . $proj_id . '/' . $request->use_id);

            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'data' => $response->json()
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'HTTP request failed: ' . $response->body()
                ], 400);
            }
        }
    }


    public function student($proj_id,$use_id,$id,Request $request) {
        $token = $request->header('Authorization');

        if ($token == "Token not found in session") {
            return $token;
        } else {

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get('http://127.0.0.1:8088/api/persons/' . $proj_id . '/' . $use_id . '/' . $id);

            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'data' => $response->json()
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'HTTP request failed: ' . $response->body()
                ], 400);
            }
        }
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

    public function viewStudentMed($code) {
        $codigoStu = DB::select("SELECT * FROM Vista_Historial_Medico_Estudiante
        WHERE stu_code = $code
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

    public function viewStudentSol($code) {
        $codigoStu = DB::select("SELECT * FROM Vista_Solicitudes_Estudiante
        WHERE stu_code = $code
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
        $codigoStu = DB::select("SELECT * FROM Vista_Actividades_Bienestar_Estudiante
        WHERE stu_code = $code
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
}
