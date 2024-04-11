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
    public static function Getbienestar_news()
{
    $solicitudesTypes = solicitudeType::all();
    foreach ($solicitudesTypes as $solicitudTypes) {
        $news = DB::table('bienestar_news')
                    ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
                    ->where('bie_new_description', "An insertion was made in the solicitudes types table'$solicitudTypes->sol_typ_id'")
                    ->select('bie_new_date', 'per_name')
                    ->get();

        if ($news->isNotEmpty()) {
            $solicitudTypes->new_date = $news[0]->bie_new_date;
            $solicitudTypes->createdBy = $news[0]->per_name;
        } else {
            $solicitudTypes->new_date = null;
            $solicitudTypes->createdBy = null;
        }
    }
    
    return $solicitudesTypes;
}
}
