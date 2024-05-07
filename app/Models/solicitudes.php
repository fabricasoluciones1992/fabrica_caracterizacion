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
        'rea_typ_id',
        'sol_typ_id',
        'stu_id'
    ];
    public $timestamps = false;
    public static function select(){
        $solicitudes = DB::select("SELECT * FROM viewSolicitudes");
        return $solicitudes;
    }
    public static function search($id){
        $solicitudes = DB::select("SELECT * FROM viewSolicitudes WHERE sol_id = $id");
        return $solicitudes[0];
    }
    
    public static function findBysol($id){
        $solicitudes = DB::select("SELECT * FROM viewSolicitudes WHERE per_document = ?",[$id]);
        return $solicitudes;
    }
}


