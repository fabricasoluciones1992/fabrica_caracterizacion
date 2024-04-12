<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MedicalHistory extends Model
{
    use HasFactory;
    protected $primaryKey = "med_his_id";
    protected $fillable = [
        'per_id',
        'dis_id',
        'med_his_status'
    ];
    public $timestamps = false;
    public static function select(){
        $mHistory = DB::select("
        SELECT mh.med_his_id, pe.per_name, di.dis_name,mh.med_his_status
        FROM medical_histories mh
        INNER JOIN persons pe ON pe.per_id = mh.per_id
        INNER JOIN diseases di ON di.dis_id = mh.dis_id");
        return $mHistory;
    }
    public static function find($id){
        $mHistory = DB::select("
        SELECT mh.med_his_id, pe.per_name, di.dis_name,mh.med_his_status
        FROM medical_histories mh
        INNER JOIN persons pe ON pe.per_id = mh.per_id
        INNER JOIN diseases di ON di.dis_id = mh.dis_id
        WHERE mh.med_his_id = $id");
        return $mHistory[0];
    }
//     public static function Getbienestar_news()
// {
//     $mHistories = MedicalHistory::select();
//     foreach ($mHistories as $mHistory) {
//         $news = DB::table('bienestar_news')
//                     ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//                     ->where('bie_new_description', "An insertion was made in the Medical Histories table'$mHistory->med_his_id'")
//                     ->select('bie_new_date', 'per_name')
//                     ->get();

//         if ($news->isNotEmpty()) {
//             $mHistory->new_date = $news[0]->bie_new_date;
//             $mHistory->createdBy = $news[0]->per_name;
//         } else {
//             $mHistory->new_date = null;
//             $mHistory->createdBy = null;
//         }
//     }
    
//     return $mHistories;
// }
}
