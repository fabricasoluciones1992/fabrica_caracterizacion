<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Disease extends Model
{
    use HasFactory;
    protected $primaryKey = 'dis_id';
    protected $table = 'diseases';

    protected $fillable = ['dis_name'];
    public $timestamps = false;
    public static function Getbienestar_news()
{
    $diseases = Disease::all();
    foreach ($diseases as $disease) {
        $news = DB::table('bienestar_news')
                    ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
                    ->where('bie_new_description', "An insertion was made in the Diseases table'$disease->dis_id'")
                    ->select('bie_new_date', 'per_name')
                    ->get();

        if ($news->isNotEmpty()) {
            $disease->new_date = $news[0]->bie_new_date;
            $disease->createdBy = $news[0]->per_name;
        } else {
            $disease->new_date = null;
            $disease->createdBy = null;
        }
    }
    
    return $diseases;
}
    
}
