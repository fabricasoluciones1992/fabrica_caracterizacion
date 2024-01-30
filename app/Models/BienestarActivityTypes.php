<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BienestarActivityTypes extends Model
{
    use HasFactory;
    protected $primaryKey = "bie_act_typ_id";
    protected $fillable = [
        'bie_act_typ_name',
    ];
    public $timestamps = false;
}
