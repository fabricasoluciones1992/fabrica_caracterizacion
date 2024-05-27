<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LearningObjects extends Model
{
    use HasFactory;
    protected $table = "learning_objects";
    protected $primaryKey = "lea_obj_id";
    protected $fillable = [
        'lea_obj_object',
        'cor_mat_id',

    ];
    public $timestamps = false;

    public static function select() {
        $learningObjects = DB::table('learning_objects')->get();
        return $learningObjects;
    }

    public static function findOne($id) {
        $learningObjects = DB::table('learning_objects')
        ->where('lea_obj_id', $id)
        ->get();
        return $learningObjects;
    }
}
