<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitudes extends Model
{
    use HasFactory;
    protected $primaryKey = "sol_id";
    protected $fillable = [
        'sol_date',
        'sol_responsible',
        'sol_status',
        'rea_id',
        'fac_id',
        'sol_typ_id',
        'stu_id'
    ];
    public $timestamps = false;
}

