<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Action extends Model
{
    use HasFactory;
    
    protected $primaryKey = "act_id";
    protected $fillable = [
        'act_name',
        'act_status',
    ];
    public $timestamps = false;

    public static function Getbienestar_news()
{
    $actions = Action::all();
    foreach ($actions as $action) {
        $news = DB::table('bienestar_news')
                    ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
                    ->where('bie_new_description', "An insertion was made in the actions table'$action->act_id'")
                    ->select('bie_new_date', 'per_name')
                    ->get();

        if ($news->isNotEmpty()) {
            $action->new_date = $news[0]->bie_new_date;
            $action->createdBy = $news[0]->per_name;
        } else {
            $action->new_date = null;
            $action->createdBy = null;
        }
    }
    
    return $actions;
}


}

    

