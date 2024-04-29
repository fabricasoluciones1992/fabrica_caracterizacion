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
        $bienestarActivity = DB::select("
        SELECT ba.bie_act_id, ba.bie_act_status,ba.bie_act_hour, ba.bie_act_date, ba.bie_act_quotas, ba.bie_act_name, bat.bie_act_typ_name, bat.bie_act_typ_id 
        FROM bienestar_activities ba
        INNER JOIN bienestar_activity_types bat ON bat.bie_act_typ_id = ba.bie_act_typ_id");
    return $bienestarActivity;
    }
    public static function search($id){
        $bienestarActivity = DB::select("
        SELECT ba.bie_act_id, ba.bie_act_status,ba.bie_act_hour, ba.bie_act_date, ba.bie_act_quotas, ba.bie_act_name, bat.bie_act_typ_name, bat.bie_act_typ_id 
        FROM bienestar_activities ba
        INNER JOIN bienestar_activity_types bat ON bat.bie_act_typ_id = ba.bie_act_typ_id
        WHERE ba.bie_act_id = $id
    ");
    return $bienestarActivity[0];
    }
    


public static function countQuotas($id)
{
    $quotas = DB::select("SELECT COUNT(*) as quotas FROM viewActivitiesBienestarStudent where ass_reg_status =1 AND bie_act_id = ".$id);
    return $quotas[0]->quotas;
}

public static function countAssitances($id)
{
    $quotas = DB::select("SELECT COUNT(*) as quotas FROM viewActivitiesBienestarStudent where ass_status = 1 AND bie_act_id = ".$id);
    return $quotas[0]->quotas;
}
}
