<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Reason extends Model
{
    
        use HasFactory;
        protected $primaryKey = "rea_typ_id";
        protected $fillable = [
            'rea_typ_name',
            'rea_typ_type',
        ];
        public $timestamps = false;
        public static function Getbienestar_news()
{
    $reasons = Reason::all();
    foreach ($reasons as $reason) {
        $news = DB::table('bienestar_news')
                    ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
                    ->where('bie_new_description', "An insertion was made in the reasons table'$reason->rea_id'")
                    ->select('bie_new_date', 'per_name')
                    ->get();

        if ($news->isNotEmpty()) {
            $reason->new_date = $news[0]->bie_new_date;
            $reason->createdBy = $news[0]->per_name;
        } else {
            $reason->new_date = null;
            $reason->createdBy = null;
        }
    }
    
    return $reasons;
}
    }
    
