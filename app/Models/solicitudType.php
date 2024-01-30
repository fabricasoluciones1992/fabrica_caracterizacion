<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitudType extends Model
{
    use HasFactory;
    protected $primaryKey = "sol_typ_id";
    protected $table = "solicitude_types";
    protected $fillable = [
      'sol_typ_name',

    ];
    public $timestamps = false;
}
