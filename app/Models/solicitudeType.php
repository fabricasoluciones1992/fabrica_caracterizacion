<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class solicitudeType extends Model
{
    use HasFactory;
    
    protected $primaryKey = "sol_typ_id";
    protected $fillable = [
      'sol_typ_name',
      'sol_typ_status',
    ];
    public $timestamps = false;

}
