<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class factor extends Model
{
    use HasFactory;

    protected $primaryKey = "fac_id";
    protected $fillable = [
        'fac_name',

    ];
    public $timestamps = false;
}
