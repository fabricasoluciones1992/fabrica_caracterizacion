<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym_assistance extends Model
{
    use HasFactory;
    protected $primaryKey = "gym_ass_id";
    protected $fillable = [
        'gym_ass_date',

        'per_id'
    ];
    public $timestamps = false;
    public static function select(){
        $gymAss = DB::select("
        SELECT ga.gym_ass_id, ga.gym_ass_date,pe.per_name 
        FROM gym_assistances ga
        INNER JOIN persons pe ON pe.per_id = ga.gym_ass_id
    ");
    return $gymAss;
    }
    public static function find($id){
        $gymAss = DB::select("
    SELECT ga.gym_ass_id, ga.gym_ass_date,pe.per_name 
        FROM gym_assistances ga
        INNER JOIN persons pe ON pe.per_id = ga.per_id
        WHERE ga.gym_ass_id = $id
    ");
    return $gymAss[0];
    }
}
