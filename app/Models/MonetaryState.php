<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class MonetaryState extends Model
{
    use HasFactory;
    protected $primaryKey = "mon_sta_id";
    protected $fillable = [
        'mon_sta_name',
        'mon_sta_status',

    ];
    public $timestamps = false;

}
