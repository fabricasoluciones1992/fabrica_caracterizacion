<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymInscription extends Model
{
    use HasFactory;
    protected $primaryKey = "gym_ins_id";
    protected $fillable = [
        'gym_ins_date',
        'gym_ins_status',
        'per_id'
    ];
    public $timestamps = false;
}

