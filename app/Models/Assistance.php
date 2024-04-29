<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assistance extends Model
{
    use HasFactory;
    protected $primaryKey = "ass_id";
    protected $fillable = [
        'ass_date',
        'ass_status',
        'stu_id',
        'ass_reg_status',
        'bie_act_id'
    ];
    public $timestamps = false;
    public static function select(){
        $assistances = DB::select("SELECT * FROM Vista_Actividades_Bienestar_Estudiante");
        return $assistances;
    }
    public static function search($id){
        $assistances =  DB::select("SELECT * FROM Vista_Actividades_Bienestar_Estudiante WHERE ass_id = $id; ");
        return $assistances[0];
    }
//     public static function Getbienestar_news()
// {
//     $assistances = Assistance::select();
//     foreach ($assistances as $assistance) {
//         $news = DB::table('bienestar_news')
//                     ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//                     ->where('bie_new_description', "An insertion was made in the assistances table'$assistance->ass_id'")
//                     ->select('bie_new_date', 'per_name')
//                     ->get();

//         if ($news->isNotEmpty()) {
//             $assistance->new_date = $news[0]->bie_new_date;
//             $assistance->createdBy = $news[0]->per_name;
//         } else {
//             $assistance->new_date = null;
//             $assistance->createdBy = null;
//         }
//     }
    
//     return $assistances;
// }

public static function countQuotas($id)
{
    $quotas = DB::select("SELECT COUNT(*) as quotas FROM viewActivitiesBienestarStudent where bie_act_id = ".$id);
    return $quotas[0]->quotas;
}
}
