<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
//car_name,pro_name buscar en el historial de carerras que sea el mismo car_id en el case 2



class Reports extends Model
{
    use HasFactory;

    public static function index($data)
    {
        switch ($data->option) {
            case "1":

                //$students = DB::select("SELECT car_name,pro_name,stu_journey,per_document,stu_code,per_name,per_lastname, use_mail, tel_number FROM viewStudents where per_typ_id = $data->data");
                $students = DB::table('viewStudents')
                    ->select('per_typ_name',  'stu_journey', 'per_document', 'per_name', 'per_lastname', 'use_mail')
                    ->where('per_typ_id', '=', $data->data)
                    ->get();
                
                return $students;
                break;
            case "2"://arreglos car_id

                // $students = DB::select("SELECT car_name,pro_name,stu_journey,per_document,stu_code,per_name,per_lastname, use_mail, tel_number FROM viewStudents where car_id = $data->data");
                $students = DB::table('viewStudents')
                    ->select('per_typ_name', 'stu_journey', 'per_document', 'per_name', 'per_lastname', 'use_mail')
                    ->where('car_id', '=', $data->data)
                    ->get();
                return $students;
                break;
            case "3"://pro y car

                $students = DB::table('viewStudents as vs')->join('viewHistorialConsultas AS hc', 'hc.per_document', '=', 'vs.per_document')->join('consultations as c', 'c.cons_id', '=', 'hc.cons_id')
                    ->select('vs.per_typ_name','vs.stu_journey', 'vs.per_document', 'vs.per_name', 'vs.per_lastname', 'vs.use_mail', 'hc.per_document',  'c.cons_reason', 'c.cons_description', 'c.cons_date')
                    ->where('c.cons_id', '=', $data->data)
                    ->get();
                return $students;

                break;
            case "4"://pro y car

                $students = DB::table('viewStudents')->rightJoin('viewActivitiesBienestarStudent AS hc', 'hc.stu_id', '=', 'viewStudents.stu_id')
                    ->select('per_typ_name','stu_journey', 'hc.per_document', 'hc.per_name', 'hc.per_lastname', 'use_mail')
                    ->where('hc.bie_act_id', '=', $data->data)
                    ->get();
                return $students;
                break;
            case "5"://pro y car
                $students = DB::table('viewStudents')->rightJoin('viewActivitiesBienestarStudent AS hc', 'hc.stu_id', '=', 'viewStudents.stu_id')
                    ->select('per_typ_name','stu_journey', 'hc.per_document', 'hc.per_name', 'hc.per_lastname', 'use_mail')
                    ->where('hc.stu_id', '=', $data->data)
                    ->get();
                return $students;
                break;
            case "6";//pro y car
                $students = DB::table('viewStudents as vs')->join('viewHistorialConsultas AS hc', 'hc.per_document', '=', 'vs.per_document')->join('consultations as c', 'c.cons_id', '=', 'hc.cons_id')
                    ->select('vs.per_typ_name','vs.stu_journey', 'vs.per_document', 'vs.per_name', 'vs.per_lastname', 'vs.use_mail', 'hc.per_document',  'c.cons_reason', 'c.cons_description', 'c.cons_date')
                    ->get();
                return $students;
                break;
            default:
                return response()->json([
                    'status' => false,
                    'message' => 'option not found'
                ],);
                break;
        }
    }


    public static function select($data)
    {
        switch ($data->option) {
            case "1":
                $students = DB::select("SELECT stu_id,pro_group, stu_journey, per_document,per_name, use_mail, per_typ_name
                FROM viewStudents WHERE per_document = $data->code");
                
                $activity = DB::select("SELECT assistances.ass_date, bienestar_activity_types.bie_act_typ_name,bienestar_activities.bie_act_name, bienestar_activities.bie_act_date, bienestar_activities.bie_act_hour FROM assistances
                INNER JOIN bienestar_activities ON assistances.bie_act_id = bienestar_activities.bie_act_id
                INNER JOIN bienestar_activity_types ON bienestar_activities.bie_act_typ_id = bienestar_activity_types.bie_act_typ_id
                WHERE assistances.stu_id = ?", [$students[0]->stu_id]);
                
                return $students;
                break;
            case "2":
                $students = DB::select("SELECT stu_id,pro_group, stu_journey, per_document,per_name, tel_number, use_mail, per_typ_name
                    FROM viewStudents WHERE per_document = $data->code");
                return $students;
                break;
            case "3":
                $students = DB::select("SELECT stu_id,pro_group, stu_journey, per_document,per_name, tel_number, use_mail, per_typ_name
                    FROM viewStudents WHERE per_document = $data->code");
                return $students;
                break;
            case "4":
                $students = DB::select("SELECT stu_id,pro_group, stu_journey, per_document,per_name, tel_number, use_mail, per_typ_name
                    FROM viewStudents WHERE per_document = $data->code");
                return $students;
                break;
            case "5":
                $students = DB::select("SELECT stu_id,pro_group, stu_journey, per_document,per_name, tel_number, use_mail, per_typ_name
                    FROM viewStudents WHERE per_document = $data->code");
                return $students;
                break;
            case "6":
                $students = DB::select("SELECT stu_id,pro_group, stu_journey, per_document,per_name, tel_number, use_mail, per_typ_name
                    FROM viewStudents WHERE per_document = $data->code");
                return $students;
                break;
            default:
                return response()->json([
                    'status' => false,
                    'message' => 'option not found'
                ],);
                break;
        }
    }
}
