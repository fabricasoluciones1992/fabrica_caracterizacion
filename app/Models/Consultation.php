<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;
    protected $primarykey = "cons_id";
    protected $fillable = [
        'cons_date',
        'cons_reason',
        'cons_description',
        'cons_weight',
        'cons_height',
        'cons_imc',
        'cons_vaccination',
    ];
    public $timestamps = false;
}
