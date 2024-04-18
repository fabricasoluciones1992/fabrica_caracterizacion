<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class enfermeria_inscription extends Model
{
    use HasFactory;
    protected $primarykey = "enf_ins_id";
    protected $fillable = [
        
        'enf_ins_weight',
        'enf_ins_height',
        'enf_ins_imc',
        'enf_ins_vaccination',
    ];
    public $timestamps = false;
    public static function search($id)
{
    $eIns=DB::select("SELECT enf_ins_id,enf_ins_weight,enf_ins_height,enf_ins_imc,enf_ins_vaccination 
    FROM enfermeria_inscriptions
    WHERE enf_ins_id=$id");
    return $eIns[0];
}

}
