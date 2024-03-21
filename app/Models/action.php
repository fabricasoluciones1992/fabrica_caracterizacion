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

    public static function getNews()
    {
        $actions = Action::all();
        // foreach ($actions as $action) {
        //     $news = DB::table('ViewNews')->where('new_description','=',"An insertion was made in the actions table'$action->act_id'");
        //     $action->per_name = $news[0]->per_name;
        //     $action->new_date = $news[0]->new_date;
        // }
        
        return $actions;
    }
}

    

