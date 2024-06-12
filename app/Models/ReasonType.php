<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ReasonType extends Model
{
    
        use HasFactory;
        protected $primaryKey = "rea_typ_id";
        protected $fillable = [
            'rea_typ_name',
            'rea_typ_type',
        ];
        public $timestamps = false;

    }
    
