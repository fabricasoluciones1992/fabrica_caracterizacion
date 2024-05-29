<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reports extends Model
{
    use HasFactory;

    public static function index($data)
    {
        switch ($data->option) {
            case "1":
                $students = DB::table('viewEnrollments')
                    ->select(
                    'stu_id as id estudiante',
                    'stu_typ_name as tipo de estudiante',
                    'stu_journey as jornada',
                    'per_document as documento',
                    'per_name as nombres',
                    'per_lastname as apellidos',
                    'use_mail as correo',
                    'car_name as programa',
                    'promotion as promocion')
                    ->where('stu_typ_id', '=', $data->data)
                    ->where('use_status', '=', 1)
                    ->get();

                return $students;
                break;

            case "2":
                $students = DB::table('viewEnrollments')
                ->select(
                    'stu_id as id_estudiante',
                    'stu_typ_name as tipo_estudiante',
                    'stu_journey as jornada',
                    'per_document as documento',
                    'per_name as nombre',
                    'per_lastname as apellido',
                    'use_mail as correo_electronico',
                    'car_name as nombre_carrera',
                    'promotion as promocion'
                )
                    ->where('car_id', '=', $data->data)
                    ->where('use_status', '=', 1)
                    ->get();

                return $students;
                break;

            case "3":
                $students = DB::table('viewSolicitudes')
                ->select(
                    'viewSolicitudes.per_document as documento',
                    'viewSolicitudes.per_name as nombres',
                    'viewSolicitudes.per_lastname as apellidos',
                    'viewSolicitudes.rea_typ_name as tipo razon',
                    'viewSolicitudes.sol_typ_name as tipo solicitud',
                    'viewSolicitudes.sol_date as fecha solicitud',
                    'viewEnrollments.promotion as promocion',
                    'viewEnrollments.car_name as programa',
                    'ViewPersons.use_mail as correo'
                )
                    ->join('viewEnrollments', 'viewSolicitudes.per_id', '=', 'viewEnrollments.per_id')
                    ->join('ViewPersons', 'viewSolicitudes.per_id', '=', 'ViewPersons.per_id')
                    ->where('viewSolicitudes.rea_typ_id', '=', $data->data)
                    ->where('viewEnrollments.stu_enr_status', '=', 1)
                    ->get();


                return $students;
                break;

            case "4":
                $students = DB::table('viewAssitances')
                ->select(
                    'viewAssitances.ass_id as id_asistencia',
                    'viewAssitances.per_name as nombre_persona',
                    'viewAssitances.per_lastname as apellido_persona',
                    'viewAssitances.per_document as documento_persona',
                    'viewAssitances.use_mail as correo_electronico',
                    'viewAssitances.stu_journey as jornada_estudiante',
                    'viewEnrollments.promotion as promocion',
                    'viewEnrollments.car_name as nombre_carrera',
                    'viewAssitances.bie_act_date as fecha_actividad',
                    'viewAssitances.bie_act_hour as hora_actividad',
                    'viewAssitances.stu_typ_name as tipo_estudiante'
                )
                    ->join('viewEnrollments', 'viewAssitances.per_id', '=', 'viewEnrollments.per_id')
                    ->where('viewAssitances.bie_act_typ_id', '=', 9)
                    ->where('viewEnrollments.stu_enr_status', '=', 1)
                    ->get();

                return $students;

                break;
                case "5":
                    $students = DB::table('viewAssitances')
                    ->select(
                        'viewAssitances.ass_id as id_asistencia',
                        'viewAssitances.per_name as nombre_persona',
                        'viewAssitances.per_lastname as apellido_persona',
                        'viewAssitances.per_document as documento_persona',
                        'viewAssitances.use_mail as correo_electronico',
                        'viewAssitances.stu_journey as jornada_estudiante',
                        'viewEnrollments.promotion as promocion',
                        'viewEnrollments.car_name as nombre_carrera',
                        'viewAssitances.bie_act_date as fecha_actividad',
                        'viewAssitances.bie_act_hour as hora_actividad',
                        'viewAssitances.stu_typ_name as tipo_estudiante',
                        'bie_act_name as nombre_actividad',
                        'ass_reg_status as estado_registro',
                        'ass_status as estado_asistencia'
                    )
                    ->join('viewEnrollments', 'viewAssitances.per_id', '=', 'viewEnrollments.per_id')
                    ->where('viewAssitances.bie_act_id', '=', $data->data)
                    ->where('viewEnrollments.stu_enr_status', '=', 1)
                    ->get();
                return $students;
                break;
            case "6";
            $students = DB::table('viewStudents')
            ->select(
                'viewStudents.stu_id as id_estudiante',
                'viewStudents.stu_typ_name as tipo_estudiante',
                'viewStudents.per_name as nombre_persona',
                'viewStudents.per_lastname as apellido_persona',
                'viewStudents.per_document as documento_persona',
                'viewStudents.per_rh as rh_persona',
                'viewStudents.per_birthdate as fecha_nacimiento',
                'viewStudents.per_direction as direccion_persona',
                'viewStudents.eps_name as nombre_eps',
                'viewStudents.stu_journey as jornada_estudiante',
                'viewEnrollments.promotion as promocion',
                'viewEnrollments.car_name as nombre_carrera',
                'consultations.cons_reason as motivo_consulta',
                'consultations.cons_description as descripcion_consulta',
                'consultations.cons_date as fecha_consulta',
                'consultations.cons_id as id_consulta'
            )
                ->join('consultations', 'consultations.per_id', '=', 'viewStudents.per_id')
                ->join('viewEnrollments', 'viewEnrollments.per_id', '=', 'viewStudents.per_id')
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
