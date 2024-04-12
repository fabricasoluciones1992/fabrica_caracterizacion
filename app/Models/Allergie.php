<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Allergie extends Model
{
    use HasFactory;
    protected $primaryKey = "all_id";
    protected $fillable = [
        'all_name'
    ];
    public $timestamps = false;
//     public static function Getbienestar_news_a()
// {
//     $allergies = Allergie::all();
//     foreach ($allergies as $allergie) {
//         $news = DB::table('bienestar_news')
//                     ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//                     ->where('bie_new_description', "An insertion was made in the Allergies table'$allergie->all_id'")
//                     ->select('bie_new_date', 'per_name')
//                     ->get();

//         if ($news->isNotEmpty()) {
//             $allergie->new_date = $news[0]->bie_new_date;
//             $allergie->per_name = $news[0]->per_name;
//         } else {
//             $allergie->new_date = null;
//             $allergie->per_name = null;
//         }
//     }
    
//     return $allergies;
// }
}
