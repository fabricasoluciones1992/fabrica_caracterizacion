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


    public function logout() {
        
    }

    public function genders() {
        $data = Controller::genders();
        return response()->json($data->original);
        
    }




    
}
