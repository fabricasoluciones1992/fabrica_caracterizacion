<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymInscription extends Model
{
    use HasFactory;
    protected $primaryKey = "gym_ins_id";
    protected $fillable = [
        'gym_ins_date',
        'gym_ins_status',
        'per_id'
    ];
    public $timestamps = false;
    public static function select(){
        $gymIns = DB::select("
        SELECT gi.gym_ins_id, gi.gym_ins_date,gi.gym_ins_status, pe.per_name 
        FROM gym_inscriptions gi
        INNER JOIN persons pe ON pe.per_id = gi.gym_ins_id
    ");
    return $gymIns;
    }
    public static function find($id){
        $gymIns = DB::select("
    SELECT gi.gym_ins_id, gi.gym_ins_date,gi.gym_ins_status, pe.per_name 
        FROM gym_inscriptions gi
        INNER JOIN persons pe ON pe.per_id = gi.per_id
        WHERE gi.gym_ins_id = $id
    ");
    return $gymIns[0];
    }
}

