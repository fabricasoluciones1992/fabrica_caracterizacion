<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assistance extends Model
{
    use HasFactory;
    protected $primaryKey = "ass_id";
    protected $fillable = [
        'ass_date',
        'ass_assistance',
        'stu_id',
        'bie_act_id'
    ];
    public $timestamps = false;
}
