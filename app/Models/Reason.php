<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    use HasFactory;
    protected $primaryKey = "rea_id";
    protected $fillable = [
      'rea_name',

    ];
    public $timestamps = false;
}
