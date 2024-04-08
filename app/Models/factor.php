<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class factor extends Model
{
    use HasFactory;

    protected $primaryKey = "fac_id";
    protected $fillable = [
        'fac_name',
        'fac_status',
    ];
    public $timestamps = false;
    public static function Getbienestar_news()
{
    $factors = factor::all();
    foreach ($factors as $factor) {
        $news = DB::table('bienestar_news')
                    ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
                    ->where('bie_new_description', "An insertion was made in the factors table'$factor->fac_id'")
                    ->select('bie_new_date', 'per_name')
                    ->get();

        if ($news->isNotEmpty()) {
            $factor->new_date = $news[0]->bie_new_date;
            $factor->createdBy = $news[0]->per_name;
        } else {
            $factor->new_date = null;
            $factor->createdBy = null;
        }
    }
    
    return $factors;
}
}