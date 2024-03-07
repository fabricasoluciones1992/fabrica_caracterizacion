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
    public static function find($id){
        $solicitudes = DB::select("SELECT * FROM ViewSolicitudes");
        return $solicitudes[0];
    }
}

