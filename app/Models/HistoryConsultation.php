<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HistoryConsultation extends Model
{
    use HasFactory;
    protected $primaryKey = "his_con_id";
    protected $fillable = [
        'cons_id',
        'stu_id',
    ];
    public $timestamp = false;
    public static function select(){
        $histcon = DB::select("SELECT * FROM viewHistorialConsultas");
        return $histcon;
    }
    public static function find($id){
        $histcon = DB::select("SELECT * FROM viewHistorialConsultas WHERE his_con_id = $id");
        return $histcon[0];
    }
}
