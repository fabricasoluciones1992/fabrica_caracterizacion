<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BienestarActivity extends Model
{
    use HasFactory;
    protected $primaryKey = "bie_act_id";
    protected $fillable = [
        'bie_act_id',
        'bie_act_date',
        'bie_act_hour',
        'bie_act_quotas',
        'bie_act_name',
        'bie_act_typ_id'
    ];
    public $timestamps = false;
    public static function select(){
        $bienestarActivity = DB::select("SELECT 
        ba.bie_act_id, 
        ba.bie_act_status, 
        ba.bie_act_hour, 
        ba.bie_act_date, 
        ba.bie_act_quotas, 
        ba.bie_act_name, 
        bat.bie_act_typ_id, 
        bat.bie_act_typ_name
    FROM 
        bienestar_activities ba
    INNER JOIN 
        bienestar_activity_types bat ON bat.bie_act_typ_id = ba.bie_act_typ_id
    ");
        return $bienestarActivity;
    }
    
    public static function search($id){
        $bienestarActivity = DB::select("
        SELECT ba.bie_act_id, ba.bie_act_status, ba.bie_act_date,ba.bie_act_hour, ba.bie_act_quotas, ba.bie_act_name, bat.bie_act_typ_name 
        FROM bienestar_activities ba
        INNER JOIN bienestar_activity_types bat ON bat.bie_act_typ_id = ba.bie_act_typ_id
        WHERE ba.bie_act_id = $id
    ");
    return $bienestarActivity[0];
    }
    public static function category($id){
        $bienestarActivity = DB::select("
            SELECT ba.bie_act_id, ba.bie_act_status, ba.bie_act_date, ba.bie_act_hour, ba.bie_act_quotas, ba.bie_act_name, bat.bie_act_typ_name 
            FROM bienestar_activities ba
            INNER JOIN bienestar_activity_types bat ON bat.bie_act_typ_id = ba.bie_act_typ_id
            WHERE ba.bie_act_typ_id = $id
            AND ba.bie_act_date >= CURDATE()
            ORDER BY ba.bie_act_date DESC
        ");
        return $bienestarActivity;
    }
    
    




public static function countQuotas($id)
{
    $quotas = DB::select("SELECT COUNT(*) as quotas FROM assistances where ass_reg_status =1 AND bie_act_id = ".$id);
    return $quotas[0]->quotas;
}

public static function countAssitances($id)
{
    $quotas = DB::select("SELECT COUNT(*) as quotas FROM assistances where ass_status = 1 AND ass_reg_status = 1 AND bie_act_id = ".$id);
    return $quotas[0]->quotas;
}
public static function findByUse($id){
    $solicitudes = DB::select("SELECT * FROM viewAssitances WHERE per_id = ?",[$id]);
    return $solicitudes;
}
public static function lastEnrollment($stu_id){
    $data = DB::table('viewEnrollments')
        ->where('stu_id', $stu_id)
        ->orderBy('stu_enr_id', 'desc')
        ->select('pro_id', 'stu_enr_semester','pro_name', 'car_id', 'car_name')
        ->first();
    return $data;
}
}
