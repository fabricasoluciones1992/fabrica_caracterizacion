<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ReasonType extends Model
{
    
        use HasFactory;
        protected $primaryKey = "rea_typ_id";
        protected $fillable = [
            'rea_typ_name',
            'rea_typ_type',
        ];
        public $timestamps = false;
//         public static function Getbienestar_news()
// {
//     $reasonTs = ReasonT::all();
//     foreach ($reasonTs as $reasonT) {
//         $news = DB::table('bienestar_news')
//                     ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//                     ->where('bie_new_description', "An insertion was made in the reasonTs table'$reasonT->rea_id'")
//                     ->select('bie_new_date', 'per_name')
//                     ->get();

//         if ($news->isNotEmpty()) {
//             $reasonT->new_date = $news[0]->bie_new_date;
//             $reasonT->createdBy = $news[0]->per_name;
//         } else {
//             $reasonT->new_date = null;
//             $reasonT->createdBy = null;
//         }
//     }
    
//     return $reasonTs;
// }
    }
    
