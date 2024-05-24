<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Consultation extends Model
{
    use HasFactory;
    protected $primaryKey = "cons_id";
    protected $fillable = [
        'cons_date',
        'cons_reason',
        'cons_description',
        'per_id'
        
    ];
    public $timestamps = false;


public static function search($id)
{
    $consultations=DB::select("SELECT cons_id,cons_date,cons_reason,cons_description 
    FROM consultations 
    WHERE cons_id=$id");
    return $consultations[0];
}
}
