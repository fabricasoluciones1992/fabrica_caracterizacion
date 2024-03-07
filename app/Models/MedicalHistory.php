<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MedicalHistory extends Model
{
    use HasFactory;
    protected $primaryKey = "med_his_id";
    protected $fillable = [
        'per_id',
        'dis_id'
    ];
    public $timestamps = false;
    public static function select(){
        $mHistory = DB::select("
        SELECT mh.med_his_id, pe.per_name, di.dis_name
        FROM medical_histories mh
        INNER JOIN persons pe ON pe.per_id = mh.per_id
        INNER JOIN diseases di ON di.dis_id = mh.dis_id");
        return $mHistory;
    }
    public static function find($id){
        $mHistory = DB::select("
        SELECT mh.med_his_id, pe.per_name, di.dis_name
        FROM medical_histories mh
        INNER JOIN persons pe ON pe.per_id = mh.per_id
        INNER JOIN diseases di ON di.dis_id = mh.dis_id
        WHERE mh.med_his_id = $id");
        return $mHistory[0];
    }
}
