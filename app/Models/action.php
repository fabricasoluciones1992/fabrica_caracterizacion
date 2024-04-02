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

    public static function getbienestar_news()
{
    $actions = Action::all();
    foreach ($actions as $action) {
        $bienestar_news = DB::table('Viewbienestar_news')
                    ->where('new_description', "An insertion was made in the actions table'$action->act_id'")
                    ->get();

        
        if ($bienestar_news->isNotEmpty()) {
            $action->per_name = $bienestar_news[0]->per_name;
            $action->new_date = $bienestar_news[0]->new_date;
        } else {

            $action->per_name = null;
            $action->new_date = null;
        }
    }
    
    return $actions;
}

}

    

