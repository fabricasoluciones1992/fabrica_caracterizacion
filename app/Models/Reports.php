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
                    ->select('stu_id', 'stu_typ_name', 'stu_journey', 'per_document', 'per_name', 'per_lastname', 'use_mail', 'car_name', 'promotion')
                    ->where('stu_typ_id', '=', $data->data)
                    ->where('use_status', '=', 1)
                    ->get();

                return $students;
                break;

            case "2":
                $students = DB::table('viewEnrollments')
                    ->select('stu_id', 'stu_typ_name', 'stu_journey', 'per_document', 'per_name', 'per_lastname', 'use_mail', 'car_name', 'promotion')
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
                    ->select('viewAssitances.ass_id', 'viewAssitances.per_name', 'viewAssitances.per_lastname', 'viewAssitances.per_document', 'viewAssitances.use_mail', 'viewAssitances.stu_journey', 'viewEnrollments.promotion', 'viewEnrollments.car_name', 'viewAssitances.bie_act_date', 'viewAssitances.bie_act_hour', 'viewAssitances.stu_typ_name')
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
                    ->where('viewEnrollments.stu_enr_status', '=', 1)
                    ->get();
                return $students;
                break;
            case "6";
                $students = DB::table('viewStudents')
                    ->join('consultations', 'viewStudents.per_id', '=', 'consultations.per_id')
                    ->join('viewEnrollments', 'viewStudents.per_id', '=', 'viewEnrollments.per_id')
                    ->select('viewStudents.stu_typ_name', 'viewStudents.per_name', 'viewStudents.per_lastname', 'viewStudents.per_document', 'viewStudents.per_rh', 'viewStudents.per_birthdate', 'viewStudents.per_direction', 'viewStudents.eps_name', 'viewStudents.stu_journey', 'viewEnrollments.promotion', 'viewEnrollments.car_name', 'consultations.cons_reason', 'consultations.cons_description', 'consultations.cons_date')
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
                $students = DB::table('viewEnrollments')
                    ->select('stu_id', 'stu_typ_name', 'stu_journey', 'per_document', 'per_name', 'per_lastname', 'use_mail', 'car_name', 'promotion')
                    ->where('stu_id', '=', $data->data)
                    ->where('use_status', '=', 1)
                    ->get();

                return $students;
                break;

            case "2":
                $students = DB::table('viewPermanences as vp')
                    ->join('persons as p','vp.per_id','=','p.per_id')
                    ->join('students as st','st.per_id','=','vp.per_id')
                    ->join('permanences as per','per.perm_id','=','vp.perm_id')
                    ->join('actions as a','a.act_id','=','per.act_id')
                    ->select('st.*','vp.*','p.*','a.*')
                    ->where('a.act_id', '=', $data->data)
                    ->get();

                return $students;
                break;
            case "3":
                $students = DB::table('viewSolicitudes as vs')
                    ->select(
                        'vs.per_id','vs.per_document','vs.per_name','vs.per_lastname','vs.rea_typ_name','vs.sol_typ_name','vs.sol_date','ve.promotion','ve.car_name','vp.use_mail')
                    ->join('viewEnrollments as ve', 'vs.per_id', '=', 've.per_id')
                    ->join('ViewPersons as vp', 'vs.per_id', '=', 'vp.per_id')
                    ->where('vs.per_id', '=', $data->data)
                    ->get();


                return $students;
                break;
            case "4":
                $students = DB::table('viewAssitances as vs')
                    ->select('vs.ass_id', 'vs.stu_id', 'vs.per_name', 'vs.per_lastname', 'vs.per_document', 'vs.use_mail', 'vs.stu_journey', 've.promotion', 've.car_name', 'vs.bie_act_date', 'vs.bie_act_hour', 'vs.stu_typ_name')
                    ->join('viewEnrollments as ve', 'vs.per_id', '=', 've.per_id')
                    ->where('vs.stu_id', '=', $data->data)
                    ->where('ve.stu_enr_status', '=', 1)
                    ->get();

                return $students;

                break;
            case "5":
                $students = DB::table('viewAssitances as va')
                    ->select('va.ass_id', 'va.stu_id','va.per_name', 'va.per_lastname', 'va.per_document', 'va.use_mail', 'va.stu_journey', 've.promotion', 've.car_name', 'va.bie_act_date', 'va.bie_act_hour', 'va.stu_typ_name', 'bie_act_name', 'ass_reg_status', 'ass_status')
                    ->join('viewEnrollments as ve', 'va.per_id', '=', 've.per_id')
                    ->where('va.stu_id', '=', $data->data)
                    ->where('ve.stu_enr_status', '=', 1)
                    ->get();
                return $students;
                break;
            case "6":
                $students = DB::table('viewStudents as vt')
                    ->join('consultations as co', 'vt.per_id', '=', 'co.per_id')
                    ->join('viewEnrollments as ve', 'vt.per_id', '=', 've.per_id')
                    ->select('vt.stu_typ_name','vt.stu_id', 'vt.per_name', 'vt.per_lastname', 'vt.per_document', 'vt.per_rh', 'vt.per_birthdate', 'vt.per_direction', 'vt.eps_name', 'vt.stu_journey', 've.promotion', 've.car_name', 'co.cons_reason', 'co.cons_description', 'co.cons_date')
                    ->where('vt.stu_id', '=', $data->data)
                    ->where('ve.stu_enr_status', '=', 1)
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
}
