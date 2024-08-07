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
        'ass_reg_status',
        'bie_act_id'
    ];
    public $timestamps = false;
    public static function select(){
        $assistances = DB::select("SELECT * FROM `viewAssitances` LIMIT 100");
        return $assistances;
    }
    public static function search($id){
        $assistances =  DB::select("SELECT * FROM `viewAssitances` WHERE ass_id = $id; ");
        return $assistances;
    }
   


}
