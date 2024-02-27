<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllergyHistory extends Model
{
    use HasFactory;
    protected $primaryKey = "all_his_id";
    protected $fillable = [
        'per_id',
        'all_id'
    ];
    public $timestamps = false;
}
