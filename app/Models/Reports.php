<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
//car_name,pro_name buscar en el historial de carerras que sea el mismo car_id en el case 2xxx



class Reports extends Model
{
    use HasFactory;

    public static function index($data)
    {
        switch ($data->option) {
            case "1":
                $students = DB::table('viewEnrollments')
                    ->select('stu_id', 'stu_typ_name', 'stu_journey', 'per_document', 'per_name', 'per_lastname', 'use_mail','car_name', 'promotion')
                    ->where('stu_typ_id', '=', $data->data)
                    ->where('use_status', '=', 1)
                    ->get();

                return $students;
                break;

            case "2":
                $students = DB::table('viewEnrollments')
                    ->select('stu_id', 'stu_typ_name', 'stu_journey', 'per_document', 'per_name', 'per_lastname', 'use_mail','car_name', 'promotion')
                    ->where('car_id', '=', $data->data)
                    ->where('use_status', '=', 1)
                    ->get();

                return $students;
                break;
                
            case "3":
                $students = DB::table('viewSolicitudes')
                ->select(
                    'viewSolicitudes.per_document',
                    'viewSolicitudes.per_name',
                    'viewSolicitudes.per_lastname',
                    'viewSolicitudes.rea_typ_name',
                    'viewSolicitudes.sol_typ_name',
                    'viewSolicitudes.sol_date',
                    'viewEnrollments.promotion',
                    'viewEnrollments.car_name',
                    'ViewPersons.use_mail'
                )
                ->join('viewEnrollments', 'viewSolicitudes.per_id', '=', 'viewEnrollments.per_id')
                ->join('ViewPersons', 'viewSolicitudes.per_id', '=', 'ViewPersons.per_id')
                ->where('viewSolicitudes.sol_typ_id', '=', $data->data)
                ->get();
            

            return $students;
            break;

            case "4":
                $students = DB::table('viewAssitances')
                ->select('viewAssitances.ass_id','viewAssitances.per_name','viewAssitances.per_lastname','viewAssitances.per_document','viewAssitances.use_mail','viewAssitances.stu_journey','viewEnrollments.promotion','viewEnrollments.car_name','viewAssitances.bie_act_date','viewAssitances.bie_act_hour','viewAssitances.stu_typ_name')
                ->join('viewEnrollments', 'viewAssitances.per_id', '=', 'viewEnrollments.per_id')
                ->where('viewAssitances.bie_act_typ_id', '=', 9)
                ->where('viewEnrollments.stu_enr_status', '=', 1)
                ->get();

                return $students;

                break;
            case "5":
                    $students = DB::table('viewAssitances')
                    ->select('viewAssitances.ass_id','viewAssitances.per_name','viewAssitances.per_lastname','viewAssitances.per_document','viewAssitances.use_mail','viewAssitances.stu_journey','viewEnrollments.promotion','viewEnrollments.car_name','viewAssitances.bie_act_date','viewAssitances.bie_act_hour','viewAssitances.stu_typ_name','bie_act_name','ass_reg_status','ass_status')
                    ->join('viewEnrollments', 'viewAssitances.per_id', '=', 'viewEnrollments.per_id')
                    ->where('viewAssitances.stu_id', '=', $data->data)
                    ->where('viewEnrollments.stu_enr_status', '=', 1)
                    ->get();
                return $students;
                break;
            case "6";
                $students = DB::table('viewStudents')
                    ->join('consultations', 'viewStudents.per_id', '=', 'consultations.per_id')
                    ->join('viewEnrollments', 'viewStudents.per_id', '=', 'viewEnrollments.per_id')
                    ->select('viewStudents.per_typ_name','viewStudents.per_name','viewStudents.per_lastname', 'viewStudents.per_document','viewStudents.per_rh','viewStudents.per_birthday','viewStudents.per_direction','viewStudents.eps_name','viewStudents.stu_journey','viewEnrollments.promotion','viewEnrollments.car_name','consultations.cons_reason', 'consultations.cons_description', 'consultations.cons_date')
                    ->where('viewEnrollments.stu_enr_status', '=', 1)
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
                $students = DB::select("SELECT stu_id,pro_group, stu_journey, per_document,per_name, use_mail, stu_typ_name
                FROM viewStudents WHERE per_document = $data->code");

                $activity = DB::select("SELECT assistances.ass_date, bienestar_activity_types.bie_act_typ_name,bienestar_activities.bie_act_name, bienestar_activities.bie_act_date, bienestar_activities.bie_act_hour FROM assistances
                INNER JOIN bienestar_activities ON assistances.bie_act_id = bienestar_activities.bie_act_id
                INNER JOIN bienestar_activity_types ON bienestar_activities.bie_act_typ_id = bienestar_activity_types.bie_act_typ_id
                WHERE assistances.stu_id = ?", [$students[0]->stu_id]);

                return $students;
                break;
            case "2":
                $students = DB::select("SELECT stu_id,pro_group, stu_journey, per_document,per_name, tel_number, use_mail, stu_typ_name
                    FROM viewStudents WHERE per_document = $data->code");
                return $students;
                break;
            case "3":
                $students = DB::select("SELECT stu_id,pro_group, stu_journey, per_document,per_name, tel_number, use_mail, stu_typ_name
                    FROM viewStudents WHERE per_document = $data->code");
                return $students;
                break;
            case "4":
                $students = DB::select("SELECT stu_id,pro_group, stu_journey, per_document,per_name, tel_number, use_mail, stu_typ_name
                    FROM viewStudents WHERE per_document = $data->code");
                return $students;
                break;
            case "5":
                $students = DB::select("SELECT stu_id,pro_group, stu_journey, per_document,per_name, tel_number, use_mail, stu_typ_name
                    FROM viewStudents WHERE per_document = $data->code");
                return $students;
                break;
            case "6":
                $students = DB::select("SELECT stu_id,pro_group, stu_journey, per_document,per_name, tel_number, use_mail, stu_typ_name
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
