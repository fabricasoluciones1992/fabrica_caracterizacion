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
    public static function search($id){
        $histcon = DB::select("SELECT * FROM viewHistorialConsultas WHERE his_con_id = $id");
        return $histcon[0];
    }
    public static function Getbienestar_news()
{
    $histcon = HistoryConsultation::select();
    foreach ($histcon as $hitcon) {
        $news = DB::table('bienestar_news')
                    ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
                    ->where('bie_new_description', "An insertion was made in the History consultations table'$hitcon->his_con_id'")
                    ->select('bie_new_date', 'per_name')
                    ->get();

        if ($news->isNotEmpty()) {
            $hitcon->new_date = $news[0]->bie_new_date;
            $hitcon->createdBy = $news[0]->per_name;
        } else {
            $hitcon->new_date = null;
            $hitcon->createdBy = null;
        }
    }
    
    return $histcon;
}
}
