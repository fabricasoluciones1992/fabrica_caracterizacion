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
        'perm_responsible',
        'perm_status',
        'sol_id',
        'act_id'
    ];
    public $timestamps = false;
    public static function select(){
        $permanences = DB::select("SELECT * FROM viewPermanences");
        return $permanences;
    }
    public static function search($id){
        $permanence = DB::select("SELECT * FROM viewPermanences WHERE perm_id = $id");
        return $permanence[0];
    }
   
    public static function findBySolTyp($id){
        $permanence = DB::select("SELECT * FROM viewPermanences WHERE sol_typ_name = ?",[$id]);
        return $permanence;
    }
    public static function findByPsol($id){
        $permanence = DB::select("SELECT * FROM viewPermanences WHERE per_document = ?",[$id]);
        return $permanence;
    }

}
