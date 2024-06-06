<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BienestarActivityTypes extends Model
{
    use HasFactory;
    protected $primaryKey = "bie_act_typ_id";
    protected $fillable = [
        'bie_act_typ_name',
        'bie_act_typ_status',
    ];
    public $timestamps = false;


}
