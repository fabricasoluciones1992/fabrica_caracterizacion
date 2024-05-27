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
    
    

    public function logout(Request $id) {
        $tokens = DB::table('personal_access_tokens')->where('tokenable_id', '=', $id->use_id)->delete();
        return response()->json([
            'status'=> true,
            'message'=> "logout success."
        ]);
    }

    public function persons($token) {
        $data = Controller::persons($token);
        return response()->json($data);
    }




    
}
