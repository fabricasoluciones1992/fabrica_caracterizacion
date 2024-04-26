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
    $enfIns = DB::select("SELECT ef.enf_ins_id, ef.enf_ins_weight, ef.enf_ins_height, ef.enf_ins_imc, ef.   enf_ins_vaccination, pe.per_id, pe.per_name, pe.per_lastname, pe.per_typ_name,pe.eps_id,pe.eps_name
        FROM enfermeria_inscriptions ef
        INNER JOIN ViewPersons pe ON pe.per_id = ef.per_id
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

        $medical_histories = DB::table('medical_histories')
                                ->join('diseases', 'medical_histories.dis_id', '=', 'diseases.dis_id')
                                ->select('medical_histories.*', 'diseases.dis_name')
                                ->where('medical_histories.per_id', '=', $enfIn->per_id)
                                ->get(); 

        $allergy_histories = DB::table('allergy_histories')
                                ->join('allergies', 'allergy_histories.all_id', '=', 'allergies.all_id')
                                ->select('allergy_histories.*', 'allergies.all_name')
                                ->where('allergy_histories.per_id', '=', $enfIn->per_id)
                                ->get(); 

        $enfIn->medical_histories = $medical_histories;
        $enfIn->allergy_histories = $allergy_histories;
    }

    return $enfIns;
}



    public static function search($id)
    {
        $enfIns = DB::select("SELECT ef.enf_ins_id, ef.enf_ins_weight, ef.enf_ins_height, ef.enf_ins_imc, ef.enf_ins_vaccination, pe.per_id, pe.per_name, pe.per_lastname, pe.per_typ_name,pe.eps_id,pe.eps_name
        FROM enfermeria_inscriptions ef
        INNER JOIN ViewPersons pe ON pe.per_id = ef.per_id
        WHERE enf_ins_id=$id");
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

        $medical_histories = DB::table('medical_histories')
                                ->join('diseases', 'medical_histories.dis_id', '=', 'diseases.dis_id')
                                ->select('medical_histories.*', 'diseases.dis_name')
                                ->where('medical_histories.per_id', '=', $enfIn->per_id)
                                ->get(); 

        $allergy_histories = DB::table('allergy_histories')
                                ->join('allergies', 'allergy_histories.all_id', '=', 'allergies.all_id')
                                ->select('allergy_histories.*', 'allergies.all_name')
                                ->where('allergy_histories.per_id', '=', $enfIn->per_id)
                                ->get(); 

        $enfIn->medical_histories = $medical_histories;
        $enfIn->allergy_histories = $allergy_histories;
    }
        return $enfIns[0];
    }
    public static function lastDisease($id){
        $lDis = DB::select("
    SELECT di.dis_name 
    FROM medical_histories mh 
    INNER JOIN diseases di ON di.dis_id = mh.dis_id 
    WHERE mh.per_id = $id 
    ORDER BY mh.med_his_id DESC 
    LIMIT 1
");
        return $lDis;
    }



}
