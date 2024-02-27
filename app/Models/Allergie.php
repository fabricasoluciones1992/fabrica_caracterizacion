<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergie extends Model
{
    use HasFactory;
    protected $primaryKey = "all_id";
    protected $fillable = [
        'all_name'
    ];
    public $timestamps = false;
}
