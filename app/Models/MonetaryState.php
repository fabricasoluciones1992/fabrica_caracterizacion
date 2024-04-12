<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class MonetaryState extends Model
{
    use HasFactory;
    protected $primaryKey = "mon_sta_id";
    protected $fillable = [
        'mon_sta_name',
        'mon_sta_status',

    ];
    public $timestamps = false;
//     public static function Getbienestar_news()
// {
//     $monStates = MonetaryState::all();
//     foreach ($monStates as $monState) {
//         $news = DB::table('bienestar_news')
//                     ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//                     ->where('bie_new_description', "An insertion was made in the monetary states table'$monState->mon_sta_id'")
//                     ->select('bie_new_date', 'per_name')
//                     ->get();

//         if ($news->isNotEmpty()) {
//             $monState->new_date = $news[0]->bie_new_date;
//             $monState->createdBy = $news[0]->per_name;
//         } else {
//             $monState->new_date = null;
//             $monState->createdBy = null;
//         }
//     }
    
//     return $monStates;
// }
}
