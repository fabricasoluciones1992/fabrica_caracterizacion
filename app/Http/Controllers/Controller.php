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
use Illuminate\Http\Request;

 
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
                ])->get('http://127.0.0.1:8088/api/persons/' . $proj_id . '/' . $use_id);
                    
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
        
        // Check if the HTTP request was successful
        if ($response->successful()) {
            // Get the token from the JSON response if present
            $responseData = $response->json();
            $token = isset($responseData['token']) ? $responseData['token'] : null;
            // Check if a token was retrieved before storing it
            if ($token !== null) {
                // Start the session and store the token
                // session_start();
                // $_SESSION['api_token'] = $token;
                // $_SESSION['use_id'] = $user->use_id;
                // $_SESSION['acc_administrator'] = $responseData['acc_administrator'];
    
                return response()->json([
                    'status' => true,
                    'data' => [
                        "token" => $token,
                        "use_id" => $user->use_id,
                        "acc_administrator" => $responseData['acc_administrator'],
                        'per_document' => $responseData['per_document']                    ]
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
    public static function findByDocument($id){
        $persons = DB::select("SELECT * FROM ViewPersons WHERE per_document = $id");
        return $persons;
    }

    public function reports(Request $request) {
        $data = Reports::index($request);
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
