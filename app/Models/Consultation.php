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
        
    ];
    public $timestamps = false;


public static function find($id)
{
    $consultations=DB::select("SELECT cons_id,cons_date,cons_reason,cons_description 
    FROM consultations 
    WHERE cons_id=$id");
    return $consultations[0];
}
}
