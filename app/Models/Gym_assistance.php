<?php
    
namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym_assistance extends Model
{
    use HasFactory;
    protected $primaryKey = "gym_ass_id";
    protected $fillable = [
        'gym_ass_date',
        'gym_ass_start',
        'per_id'
    ];
    public $timestamps = false;
    public static function select() {
        $gymAss = DB::select("
            SELECT 
                ga.gym_ass_id, 
                ga.gym_ass_date,
                pe.per_name,
                pe.per_document,
                pe.per_lastname,
               
                pe.doc_typ_id,
                pe.doc_typ_name

            FROM 
                gym_assistances ga
            INNER JOIN 
                ViewPersons pe ON pe.per_id = ga.per_id
        ");
        return $gymAss;
    }
    
    public static function find($id){
        $gymAss = DB::select("
        SELECT 
        ga.gym_ass_id, 
        ga.gym_ass_date,
        pe.per_name,
        pe.per_document,
        pe.per_lastname,
        
        pe.doc_typ_id,
        pe.doc_typ_name
    FROM 
        gym_assistances ga
    INNER JOIN 
        persons pe ON pe.per_id = ga.per_id
        WHERE ga.gym_ass_id = $id
    ");
    return $gymAss[0];
    }
    
//     public static function Getbienestar_news()
// {
//     $gymAss = Gym_assistance::select();
//     foreach ($gymAss as $gymAs) {
//         $news = DB::table('bienestar_news')
//                     ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//                     ->where('bie_new_description', "An insertion was made in the Gym assistances table'$gymAs->gym_ass_id'")
//                     ->select('bie_new_date', 'per_name')
//                     ->get();

//         if ($news->isNotEmpty()) {
//             $gymAs->new_date = $news[0]->bie_new_date;
//             $gymAs->createdBy = $news[0]->per_name;
//         } else {
//             $gymAs->new_date = null;
//             $gymAs->createdBy = null;
//         }
//     }
    
//     return $gymAss;
// }

}
