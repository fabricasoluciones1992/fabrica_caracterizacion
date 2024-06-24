<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Action extends Model
{
    use HasFactory;
    
    protected $primaryKey = "act_id";
    protected $fillable = [
        'act_name',
        'act_status',
    ];
    public $timestamps = false;




}

    

