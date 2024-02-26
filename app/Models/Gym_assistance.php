<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym_assistance extends Model
{
    use HasFactory;
    protected $primaryKey = "gym_ass_id";
    protected $fillable = [
        'gym_ass_date',

        'per_id'
    ];
    public $timestamps = false;
}
