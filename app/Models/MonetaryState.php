<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonetaryState extends Model
{
    use HasFactory;
    protected $primaryKey = "mon_sta_id";
    protected $fillable = [
        'mon_sta_name'
    ];
    public $timestamps = false;
}
