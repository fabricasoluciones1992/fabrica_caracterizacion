<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitud extends Model
{
    use HasFactory;
    protected $primaryKey = "sol_id";
    protected $table="solicitudes";
    protected $fillable = [
        'sol_date',
        'sol_description',
        'sol_typ_id',
        'fac_id',
        'stu_id'

    ];
    public $timestamps = false;
}
