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
        'rea_id',
        'sol_typ_id',
        'stu_id'
    ];
    public $timestamps = false;
}

