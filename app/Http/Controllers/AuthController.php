<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){

        $response = Http::post("http://127.0.0.1:8088/api/login", [
            "use_mail" => $request->use_mail,
            "use_password" => Hash::make($request->use_password)
        ]);
        $user=DB::table('users')->where("use_mail",'=',$request->use_mail)->first();
        $user = User::find($user->use_id);
            // $tokens = DB::table('personal_access_tokens')->where('tokenable_id', '=', $user->use_id)->delete();
            // Auth::login($user);
        Auth::login($user);
        
    
        // Verifica si la solicitud HTTP fue exitosa
        if ($response->successful()) {
            // Obtener el token de la respuesta JSON, si está presente
            $responseData = $response->json();
            $token = isset($responseData['token']) ? $responseData['token'] : null;
    
            // Verifica si se obtuvo un token antes de almacenarlo
            if ($token !== null) {
                // Iniciar la sesión y almacenar el token
                session_start();
                $_SESSION['api_token'] = $token;
    
                return response()->json([
                    'status' => true,
                    'data' => $token
                ]);
            } else {
                // Manejar el caso en el que 'token' no está presente en la respuesta
                return response()->json([
                    'status' => false,
                    'message' => 'Token not found in the response'
                ]);
            }
        } else {
            // Manejar el caso en el que la solicitud HTTP no fue exitosa
            return response()->json([
                'status' => false,
                'message' => 'HTTP request failed'
            ]);
        }

    }



    public function register(Request $request){
        $rules = [
            'use_mail'=> 'required|min:1|max:250|email|unique:users',
            'use_password'=> 'required|min:1|max:150|string',
            'per_name'=> 'required|min:1|max:150|string',
            'per_lastname'=> 'required|min:1|max:100|string',
            'per_document'=> 'required|min:1000|max:999999999999999|integer',
            'per_expedition'=> 'required|date',
            'per_birthdate'=> 'required|date',
            'per_direction'=> 'required|min:1|max:255|string',
            'civ_sta_id'=> 'required|integer',
            'doc_typ_id'=> 'required|integer',
            'eps_id'=> 'required|integer',
            'gen_id'=> 'required|integer',
            'con_id'=> 'required|integer',
            'mul_id'=> 'required|integer',
        ];

        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ],400);
        }else{
            $user = User::create([
                'use_mail' => $request->use_mail,
                'use_password' => Hash::make($request->use_password),
                'use_status' => 1
            ]);
            $person = Person::create([
                'per_name'=> $request->per_name,
                'per_lastname'=> $request->per_lastname,
                'per_document'=> $request->per_document,
                'per_expedition'=> $request->per_expedition,
                'per_birthdate'=> $request->per_birthdate,
                'per_direction'=> $request->per_direction,
                'civ_sta_id'=> $request->civ_sta_id,
                'doc_typ_id'=> $request->doc_typ_id,
                'eps_id'=> $request->eps_id,
                'gen_id'=> $request->gen_id,
                'con_id'=> $request->con_id,
                'mul_id'=> $request->mul_id,
                'use_id'=> $user->use_id,
            ]);
            Controller::NewRegisterTrigger("Se Registro un usuario: $request->name",3,6,1);
            return response()->json([
                'status' => True,
                'message' => "User created successfully",
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ],200);
        }
    }

    public function logout() {
        
    }

    public function genders() {
        session_start();
    
        if (isset($_SESSION['api_token'])) {
            $token = $_SESSION['api_token'];
    
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get('http://127.0.0.1:8088/api/genders');
    
            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'HTTP request failed'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Token not found in session'
            ]);
        }
    }




    
}
