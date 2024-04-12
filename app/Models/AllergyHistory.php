<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllergyHistory extends Model
{
    use HasFactory;
    protected $primaryKey = "all_his_id";
    protected $fillable = [
        'per_id',
        'all_id'
    ];
    public $timestamps = false;

    public static function select(){
        $aHistory = DB::select("
        SELECT ah.all_his_id, pe.per_name, al.all_name
        FROM allergy_histories ah
        INNER JOIN persons pe ON pe.per_id = ah.per_id
        INNER JOIN allergies al ON al.all_id = ah.all_id
    ");
    return $aHistory;
    }
    public static function find($id){
        $aHistory = DB::select("
    SELECT ah.all_his_id, pe.per_name, al.all_name
        FROM allergy_histories ah
        INNER JOIN persons pe ON pe.per_id = ah.per_id
        INNER JOIN allergies al ON al.all_id = ah.all_id
        WHERE ah.all_his_id = $id
    ");
    return $aHistory[0];

    }
//     public static function Getbienestar_news()
// {
//     $aHistories = AllergyHistory::select();
//     foreach ($aHistories as $aHistory) {
//         $news = DB::table('bienestar_news')
//                     ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//                     ->where('bie_new_description', "An insertion was made in the Allergies Histories table'$aHistory->all_his_id'")
//                     ->select('bie_new_date', 'per_name')
//                     ->get();

//         if ($news->isNotEmpty()) {
//             $aHistory->new_date = $news[0]->bie_new_date;
//             $aHistory->createdBy = $news[0]->per_name;
//         } else {
//             $aHistory->new_date = null;
//             $aHistory->createdBy = null;
//         }
//     }
    
//     return $aHistories;
// }
}
