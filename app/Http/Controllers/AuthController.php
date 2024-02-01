<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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
        return Auth::user();
    
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
            'name' => 'required|string|min:1|max:10',
            'email' => 'required|string|min:1|max:100|unique:users|email',
            'password' => 'required|string|min:1|max:100',
        ];

        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ],400);
        }else{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
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
            ])->post('http://127.0.0.1:8088/api/genders');
    
            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $response->body()
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
