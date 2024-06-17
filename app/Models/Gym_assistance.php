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
                ga.gym_ass_start,
                pe.per_name,
                pe.per_document,
                pe.per_lastname,
                pe.doc_typ_id,
                pe.doc_typ_name,
                pe.use_mail
            FROM 
                gym_assistances ga
            INNER JOIN 
                ViewPersons pe ON pe.per_id = ga.per_id
            ORDER BY gym_ass_date DESC

            
        ");
        return $gymAss;
    }
    
    public static function find($id){
        $gymAss = DB::select("
       SELECT 
                ga.gym_ass_id, 
                ga.gym_ass_date,
                ga.gym_ass_start,
                pe.per_name,
                pe.per_document,
                pe.per_lastname,
                pe.doc_typ_id,
                pe.doc_typ_name,
                pe.use_mail
            FROM 
                gym_assistances ga
            INNER JOIN 
                ViewPersons pe ON pe.per_id = ga.per_id
        WHERE ga.gym_ass_id = $id
    ");
    return $gymAss[0];
    }
    public static function selectByGymAss($startDate,$endDate) {
        $gymAss = DB::select("
           SELECT 
                ga.gym_ass_id, 
                ga.gym_ass_date,
                ga.gym_ass_start,
                pe.per_name,
                pe.per_document,
                pe.per_lastname,
                pe.doc_typ_id,
                pe.doc_typ_name,
                pe.use_mail
            FROM 
                gym_assistances ga
            INNER JOIN 
                ViewPersons pe ON pe.per_id = ga.per_id
            WHERE
                ga.gym_ass_date BETWEEN ? AND ?
        ", [$startDate,$endDate]);
    
        return $gymAss;
    }
    
    

}
