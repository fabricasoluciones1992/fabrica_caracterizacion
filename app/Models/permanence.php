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
//     public static function Getbienestar_news()
// {
//     $permanences = permanence::select();
//     foreach ($permanences as $permanence) {
//         $news = DB::table('bienestar_news')
//                     ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//                     ->where('bie_new_description', "An insertion was made in the permanences table'$permanence->perm_id'")
//                     ->select('bie_new_date', 'per_name')
//                     ->get();

//         if ($news->isNotEmpty()) {
//             $permanence->new_date = $news[0]->bie_new_date;
//             $permanence->createdBy = $news[0]->per_name;
//         } else {
//             $permanence->new_date = null;
//             $permanence->createdBy = null;
//         }
//     }
    
//     return $permanences;
// }
}
