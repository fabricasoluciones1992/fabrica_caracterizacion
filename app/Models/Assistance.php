<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assistance extends Model
{
    use HasFactory;
    protected $primaryKey = "ass_id";
    protected $fillable = [
        'ass_date',
        'ass_status',
        'stu_id',
        'bie_act_id'
    ];
    public $timestamps = false;
    public static function select(){
        $assistances = DB::select("SELECT * FROM Vista_Actividades_Bienestar_Estudiante");
        return $assistances;
    }
    public static function find($id){
        $assistances =  DB::select("SELECT * FROM Vista_Actividades_Bienestar_Estudiante WHERE ass_id = $id; ");
        return $assistances[0];
    }
}
