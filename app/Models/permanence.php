<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class permanence extends Model
{
    use HasFactory;
    protected $primaryKey = "perm_id";
    protected $fillable = [
        'perm_date',
        'perm_description',
        'emp_id',
        'perm_status',
        'sol_id',
        'act_id'
    ];
    public $timestamps = false;
    public static function select()
{
    $permanences = DB::select("SELECT * FROM viewPermanences ORDER BY perm_date DESC");

    return $permanences;
}

    public static function search($id){
        $permanence = DB::select("SELECT * FROM viewPermanences WHERE perm_id = $id");
        return $permanence[0];
    }
   
    public static function findBySolTyp($id,$sol_typ_name){
        $permanence = DB::select("SELECT * FROM viewPermanences WHERE sol_typ_id = ? AND sol_typ_name = ?",[$id,$sol_typ_name]);
        return $permanence;
    }
    public static function findByPsol($id){
        $permanence = DB::select("SELECT * FROM viewPermanences WHERE per_document = ?",[$id]);
        return $permanence;
    }

}
