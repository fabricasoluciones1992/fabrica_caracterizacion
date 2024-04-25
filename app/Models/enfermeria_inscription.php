<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class enfermeria_inscription extends Model
{
    use HasFactory;

    protected $primaryKey = "enf_ins_id";
    protected $fillable = [
        'enf_ins_weight',
        'enf_ins_height',
        'enf_ins_imc',
        'enf_ins_vaccination',
        'per_id',
    ];
    public $timestamps = false;

    public static function select()
{
    $enfIns = DB::select("
        SELECT ef.enf_ins_id, ef.enf_ins_weight, ef.enf_ins_height, ef.enf_ins_imc, ef.enf_ins_vaccination, pe.per_id, pe.per_name, pe.per_lastname, pe.per_typ_name
        FROM enfermeria_inscriptions ef
        INNER JOIN Viewpersons pe ON pe.per_id = ef.per_id
    ");

    foreach ($enfIns as $enfIn) {
        $imc = $enfIn->enf_ins_imc;
        if ($imc < 18.5) {
            $enfIn->imc_status = "Bajo";
        } elseif ($imc >= 18.5 && $imc < 25) {
            $enfIn->imc_status = "Normal";
        } elseif ($imc >= 25 && $imc < 30) {
            $enfIn->imc_status = "Sobrepeso";
        } else {
            $enfIn->imc_status = "Obesidad";
        }

        $medical_histories = DB::table('medical_histories')->where('per_id', '=', $enfIn->per_id)->get(); 
        $allergy_histories = DB::table('allergy_histories')->where('per_id', '=', $enfIn->per_id)->get(); 
        $enfIn->medical_histories = $medical_histories;
        $enfIn->allergy_histories = $allergy_histories;
    }

    return $enfIns;
}


    public static function search($id)
    {
        $eIns = DB::select("SELECT enf_ins_id, enf_ins_weight, enf_ins_height, enf_ins_imc, enf_ins_vaccination 
        FROM enfermeria_inscriptions
        WHERE enf_ins_id=$id");
        return $eIns[0];
    }
}