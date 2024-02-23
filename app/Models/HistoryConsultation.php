<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryConsultation extends Model
{
    use HasFactory;
    protected $primaryKey = "his_con_id";
    protected $fillable = [
        'cons_id',
        'stu_id',
    ];
    public $timestamp = false;
}
