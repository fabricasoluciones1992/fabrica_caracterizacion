<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymInscription extends Model
{
    use HasFactory;
    protected $primaryKey = "gym_ins_id";
    protected $fillable = [
        'gym_ins_date',
        'gym_ins_status',
        'per_id'
    ];
    public $timestamps = false;
    public static function select() {
        $gymIns = DB::select("
            SELECT 
                gi.gym_ins_id, 
                gi.gym_ins_date,
                gi.gym_ins_status,
                pe.per_name,
                pe.per_document,
                pe.per_lastname,
                
                pe.doc_typ_id,
                pe.doc_typ_name
            FROM 
                gym_inscriptions gi
            INNER JOIN 
                ViewPersons pe ON pe.per_id = gi.per_id
        ");
        return $gymIns;
    }
    
    public static function search($id){
        $gymIns = DB::select("
        SELECT 
        gi.gym_ins_id, 
        gi.gym_ins_date,
        gi.gym_ins_status,
        pe.per_name,
        pe.per_document,
        pe.per_lastname,
        
        pe.doc_typ_id,
        pe.doc_typ_name
    FROM 
        gym_inscriptions gi
    INNER JOIN 
        ViewPersons pe ON pe.per_id = gi.per_id
        WHERE gi.gym_ins_id = $id
    ");
    return $gymIns[0];
    }
    
    

//     public static function Getbienestar_news()
// {
//     $gymIns = GymInscription::select();
//     foreach ($gymIns as $gymIn) {
//         $news = DB::table('bienestar_news')
//                     ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//                     ->where('bie_new_description', "An insertion was made in the Gym inscriptions table'$gymIn->gym_ins_id'")
//                     ->select('bie_new_date', 'per_name')
//                     ->get();

//         if ($news->isNotEmpty()) {
//             $gymIn->new_date = $news[0]->bie_new_date;
//             $gymIn->createdBy = $news[0]->per_name;
//         } else {
//             $gymIn->new_date = null;
//             $gymIn->createdBy = null;
//         }
//     }
    
//     return $gymIns;
// }
}

