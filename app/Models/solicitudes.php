<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class solicitudes extends Model
{
    use HasFactory;
    protected $primaryKey = "sol_id";
    protected $fillable = [
        'sol_date',
        'sol_responsible',
        'sol_status',
        'rea_id',
        'fac_id',
        'sol_typ_id',
        'stu_id'
    ];
    public $timestamps = false;
    public static function select(){
        $solicitudes = DB::select("SELECT * FROM ViewSolicitudes");
        return $solicitudes;
    }
    public static function search($id){
        $solicitudes = DB::select("SELECT * FROM ViewSolicitudes WHERE sol_id = $id");
        return $solicitudes[0];
    }
//     public static function Getbienestar_news()
// {
//     $solicitudes = solicitudes::select();
//     foreach ($solicitudes as $solicitud) {
//         $news = DB::table('bienestar_news')
//                     ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//                     ->where('bie_new_description', "An insertion was made in the solicitudes table'$solicitud->sol_id'")
//                     ->select('bie_new_date', 'per_name')
//                     ->get();

//         if ($news->isNotEmpty()) {
//             $solicitud->new_date = $news[0]->bie_new_date;
//             $solicitud->createdBy = $news[0]->per_name;
//         } else {
//             $solicitud->new_date = null;
//             $solicitud->createdBy = null;
//         }
//     }
    
//     return $solicitudes;
// }
}

