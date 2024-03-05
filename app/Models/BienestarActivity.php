<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BienestarActivity extends Model
{
    use HasFactory;
    protected $primaryKey = "bie_act_id";
    protected $fillable = [
        'bie_act_id',
        'bie_act_date',
        'bie_act_hour',
        'bie_act_quotas',
        'bie_act_name',
        'bie_act_typ_id'
    ];
    public $timestamps = false;
}
