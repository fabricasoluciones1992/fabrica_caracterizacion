<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Consultation extends Model
{
    use HasFactory;//agregar tipo de persona
    protected $primarykey = "cons_id";
    protected $fillable = [
        'cons_date',
        'cons_reason',
        'cons_description',
        'per_id'
        
    ];
    public $timestamps = false;

public static function select(){
    $cons= DB::select("SELECT co.cons_id,co.cons_date,co.cons_reason,co.cons_description,pe.per_id,pe.per_name,pe.per_lastname,pe.per_typ_id,pe.per_typ_name
    FROM consultations co
    INNER JOIN ViewPersons pe ON pe.per_id = co.per_id");
    return $cons;
}
public static function search($id)
{
    $consultations=DB::select("SELECT co.cons_id,co.cons_date,co.cons_reason,co.cons_description,pe.per_id,pe.per_name,pe.per_lastname,pe.per_typ_id,pe.per_typ_name
    FROM consultations co
    INNER JOIN ViewPersons pe ON pe.per_id = co.per_id  
    WHERE cons_id=$id");
    return $consultations[0];
}
}
