<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BienestarActivityTypes extends Model
{
    use HasFactory;
    protected $primaryKey = "bie_act_typ_id";
    protected $fillable = [
        'bie_act_typ_name',
        'bie_act_typ_status',
    ];
    public $timestamps = false;
//     public static function Getbienestar_news()
// {
//     $bienestarActTypes = BienestarActivityTypes::all();
//     foreach ($bienestarActTypes as $bienestarActType) {
//         $news = DB::table('bienestar_news')
//                     ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//                     ->where('bie_new_description', "An insertion was made in the Bienestar Activities types table'$bienestarActType->bie_act_typ_id'")
//                     ->select('bie_new_date', 'per_name')
//                     ->get();

//         if ($news->isNotEmpty()) {
//             $bienestarActType->new_date = $news[0]->bie_new_date;
//             $bienestarActType->createdBy = $news[0]->per_name;
//         } else {
//             $bienestarActType->new_date = null;
//             $bienestarActType->createdBy = null;
//         }
//     }
    
//     return $bienestarActTypes;
// }
}
