<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Consultation extends Model
{
    use HasFactory;
    protected $primarykey = "cons_id";
    protected $fillable = [
        'cons_date',
        'cons_reason',
        'cons_description',
        'cons_weight',
        'cons_height',
        'cons_imc',
        'cons_vaccination',
    ];
    public $timestamps = false;
//     public static function Getbienestar_news()
// {
//     $consultations = Consultation::all();
//     foreach ($consultations as $consultation) {
//         $news = DB::table('bienestar_news')
//                     ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//                     ->where('bie_new_description', "An insertion was made in the consultations table'$consultation->cons_id'")
//                     ->select('bie_new_date', 'per_name')
//                     ->get();

//         if ($news->isNotEmpty()) {
//             $consultation->new_date = $news[0]->bie_new_date;
//             $consultation->createdBy = $news[0]->per_name;
//         } else {
//             $consultation->new_date = null;
//             $consultation->createdBy = null;
//         }
//     }
    
//     return $consultations;
// }

public static function find($id)
{
    $consultations=DB::select("SELECT cons_id,cons_date,cons_reason,cons_description,cons_weight,cons_height,cons_imc,cons_vaccination 
    FROM consultations 
    WHERE cons_id=$id");
    return $consultations[0];
}
}
