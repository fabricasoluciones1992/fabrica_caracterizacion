<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Solicitudes extends Model
{
    use HasFactory;
    protected $primaryKey = "sol_id";
    protected $fillable = [
        'sol_date',
        'emp_id',
        'sol_status',
        'rea_typ_id',
        'sol_typ_id',
        'stu_id'
    ];
    public $timestamps = false;
    public static function select()
{
    $Solicitudes = DB::select("SELECT * FROM viewSolicitudes ORDER BY sol_id DESC");

    $SolicitudesType0 = [];
    $SolicitudesType1 = [];

    foreach ($Solicitudes as $solicitud) {
        $solicitud->status_name = Solicitudes::getStatusName($solicitud->sol_status);

        if ($solicitud->rea_typ_type == 0) {
            $SolicitudesType0[] = $solicitud;
        } elseif ($solicitud->rea_typ_type == 1) {
            $SolicitudesType1[] = $solicitud;
        }
    }

    return [
        'reason' => $SolicitudesType0,
        'factor' => $SolicitudesType1,
    ];
}


    public static function getStatusName($status) {
        switch ($status) {
            case 0:
                return 'Recibida';
            case 1:
                return 'En curso';
            case 2:
                return 'Gestionada';
            case 3:
                return 'Cancelada';
            case 4:
                return 'Remisión interna';
            case 5:
                return 'Remisión externa';
            
        }
    
    }
    public static function search($id){
    $solicitud = DB::select("SELECT * FROM viewSolicitudes WHERE sol_id = $id");

    if (!empty($solicitud)) {
        $solicitud = $solicitud[0];
        $solicitud->status_name = Solicitudes::getStatusName($solicitud->sol_status);
    }
    
    return $solicitud;
}
public static function findBystatus($id){
    $Solicitudes = DB::select("SELECT * FROM viewSolicitudes WHERE sol_status = ?",[$id]);
    foreach ($Solicitudes as $solicitud) {
        $solicitud->status_name = Solicitudes::getStatusName($solicitud->sol_status);
    }
    return $Solicitudes;
}
    public static function findBysol($id){
        $Solicitudes = DB::select("SELECT * FROM viewSolicitudes WHERE per_document = '$id'");
        foreach ($Solicitudes as $solicitud) {
            $solicitud->status_name = Solicitudes::getStatusName($solicitud->sol_status);
        }
        return $Solicitudes;
    }
    public static function findByUse($id, $rea_typ_type = null){
        if ($rea_typ_type !== null) {
            $Solicitudes = DB::select("SELECT * FROM viewSolicitudes WHERE per_id = ? AND rea_typ_type = ?", [$id, $rea_typ_type]);
        } else {
            $Solicitudes = DB::select("SELECT * FROM viewSolicitudes WHERE per_id = ?", [$id]);
        }
        foreach ($Solicitudes as $solicitud) {
            $solicitud->status_name = Solicitudes::getStatusName($solicitud->sol_status);
        }
        return $Solicitudes;
    }
    

}


