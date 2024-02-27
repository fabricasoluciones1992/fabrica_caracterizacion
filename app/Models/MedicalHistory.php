<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    use HasFactory;
    protected $primaryKey = "med_his_id";
    protected $fillable = [
        'per_id',
        'dis_id'
    ];
    public $timestamps = false;
}
