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
                        'stu_id as Id Estudiante',
                        'stu_typ_name as Tipo de Estudiante',
                        'stu_journey as Jornada',
                        'per_document as Documento',
                        'per_name as Nombres',
                        'per_lastname as Apellidos',
                        'use_mail as Correo',
                        'car_name as Programa',
                        'promotion as Promoción'
                    )
                    ->where('stu_typ_id', '=', $data->data)
                    ->where('use_status', '=', 1)
                    ->get();

                return $students;
                break;

            case "2":
                $students = DB::table('viewEnrollments')
                ->select(
                    'stu_id as Id estudiante',
                    'stu_typ_name as Tipo estudiante',
                    'stu_journey as Jornada',
                    'per_document as Documento',
                    'per_name as Nombre',
                    'per_lastname as Apellido',
                    'use_mail as Correo electronico',
                    'car_name as Nombre carrera',
                    'promotion as Promocion'
                )
                    ->where('car_id', '=', $data->data)
                    ->where('use_status', '=', 1)
                    ->get();

                return $students;
                break;

            case "3":
                $students = DB::table('viewSolicitudes')
                ->select(
                    'viewSolicitudes.per_document as Documento',
                    'viewSolicitudes.per_name as Nombres',
                    'viewSolicitudes.per_lastname as Apellidos',
                    'viewSolicitudes.rea_typ_name as Tipo razón',
                    'viewSolicitudes.sol_typ_name as Tipo solicitud',
                    'viewSolicitudes.sol_date as Fecha solicitud',
                    'viewEnrollments.promotion as Promoción',
                    'viewEnrollments.car_name as Programa',
                    'ViewPersons.use_mail as Correo'
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
                    'viewAssitances.ass_id as Id asistencia',
                    'viewAssitances.per_name as Nombre persona',
                    'viewAssitances.per_lastname as Apellido persona',
                    'viewAssitances.per_document as Documento persona',
                    'viewAssitances.use_mail as Correo electrónico',
                    'viewAssitances.stu_journey as Jornada estudiante',
                    'viewEnrollments.promotion as Promoción',
                    'viewEnrollments.car_name as Nombre carrera',
                    'viewAssitances.bie_act_date as Fecha actividad',
                    'viewAssitances.bie_act_hour as Hora actividad',
                    'viewAssitances.stu_typ_name as Tipo estudiante'
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
                        'viewAssitances.ass_id as Id asistencia',
                        'viewAssitances.per_name as Nombre persona',
                        'viewAssitances.per_lastname as Apellido persona',
                        'viewAssitances.per_document as Documento persona',
                        'viewAssitances.use_mail as Correo electrónico',
                        'viewAssitances.stu_journey as Jornada estudiante',
                        'viewEnrollments.promotion as Promoción',
                        'viewEnrollments.car_name as Nombre carrera',
                        'viewAssitances.bie_act_date as Fecha actividad',
                        'viewAssitances.bie_act_hour as Hora actividad',
                        'viewAssitances.stu_typ_name as Tipo estudiante',
                        'bie_act_name as Nombre actividad',
                        'ass_reg_status as Estado registro',
                        'ass_status as Estado asistencia'
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
                'viewStudents.stu_id as Id estudiante',
                'viewStudents.stu_typ_name as Tipo estudiante',
                'viewStudents.per_name as Nombre persona',
                'viewStudents.per_lastname as Apellido persona',
                'viewStudents.per_document as Documento persona',
                'viewStudents.per_rh as RH persona',
                'viewStudents.per_birthdate as Fecha nacimiento',
                'viewStudents.per_direction as Dirección persona',
                'viewStudents.eps_name as Nombre EPS',
                'viewStudents.stu_journey as Jornada estudiante',
                'viewEnrollments.promotion as Promoción',
                'viewEnrollments.car_name as Nombre carrera',
                'consultations.cons_reason as Motivo consulta',
                'consultations.cons_description as Descripción consulta',
                'consultations.cons_date as Fecha consulta',
                'consultations.cons_id as Id consulta'
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
                    ->join('viewEnrollments as ve','ve.per_id','=','vp.per_id')
                    ->join('students as st','st.per_id','=','vp.per_id')
                    ->join('permanences as per','per.perm_id','=','vp.perm_id')
                    ->join('actions as a','a.act_id','=','per.act_id')
                    ->join('promotions as pr','pr.pro_id','=','ve.pro_id')
                    // ->join('telephones as te','te.per_id','=','vp.per_id')
                    ->select('vp.per_id','vp.act_name as Acción','ve.car_name as Carrera','pr.pro_name as Promoción','pr.pro_group as Grupo','st.stu_journey as Jornada','vp.per_document as Documento','vp.per_name as Nombre','vp.per_lastname as Apellido','ve.use_mail as Correo institucional','vp.sol_typ_name as Solicitud','vp.perm_date as Fecha de gestión','vp.rea_typ_name as Motivo de estado')
                    ->where('a.act_id', '=', $data->data)
                    ->get();


                    foreach($students as $stu){
                        $tel = DB::table('telephones as tel')->where('tel.per_id','=',$stu->per_id)->select('tel_number as Teléfono','tel_description as Descripcion teléfono')->max('tel_number');
                        $stu->telefono = $tel;
                    }

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
