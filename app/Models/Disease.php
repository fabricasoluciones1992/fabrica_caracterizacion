<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Disease extends Model
{
    use HasFactory;
    protected $primaryKey = 'dis_id';
    protected $table = 'diseases';
    protected $fillable = ['dis_name'];
    public $timestamps = false;
}
