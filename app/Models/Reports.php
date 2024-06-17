<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\For_;
use stdClass;

class Reports extends Model
{
    use HasFactory;

    public static function index($data)
    {
        switch ($data->option) {
            case "1":
                $students = DB::table('viewStudents')
                    ->select(
                        'stu_id as ID',
                        'stu_typ_name as Tipo de Estudiante',
                        'per_document as Documento',
                        'per_name as Nombres',
                        'per_lastname as Apellidos',
                        'use_mail as Correo',
                    )
                    ->where('stu_typ_id', '=', $data->data)
                    ->where('use_status', '=', 1)
                    ->get();
                    foreach ($students as $student) {
                        $lastStudent = Controller::lastEnrollments($student->ID);
                        if ($lastStudent == []) {
                            $student->Promocion = "N/A";
                            $student->Carrera = "N/A";
                            $student->Jornada  = "N/A";
                        }else{
                            $student->Promocion = $lastStudent->pro_name;
                            $student->Carrera = $lastStudent->car_name;
                            $student->Jornada  = $lastStudent->stu_enr_journey;
                            if ($student->Jornada == 0) {
                                $student->Jornada = "diurno";
                            } elseif($student->Jornada == 1) {
                                $student->Jornada = "nocturno";
                            }else{
                                $student->Jornada = "N/A";
                            }
                        }
                    }
                return $students;
                break;

            case "2":
                $preStudens = DB::table('students')->get();
                $students = array();
                foreach ($preStudens as $student) {
                    $lastStudent = Controller::lastEnrollments($student->stu_id);
                    $finalStudent = array();
                    if ($lastStudent && $lastStudent->car_id == $data->data) {
                        $finalStudent[0] = new stdClass();
                        $finalStudent[0]->{'Id estudiante'} = $lastStudent->stu_id ;
                        $finalStudent[0]->{'Tipo Estudiante'} = $lastStudent->stu_typ_name;
                        $finalStudent[0]->Jornada = $lastStudent->stu_enr_journey ;
                        if ($finalStudent[0]->Jornada == 0) {
                            $finalStudent[0]->Jornada = "diurno";
                        } elseif($finalStudent[0]->Jornada == 1) {
                            $finalStudent[0]->Jornada = "nocturno";
                        }else{
                            $finalStudent[0]->Jornada = "N/A";
                        }
                        $finalStudent[0]->Documento = $lastStudent->per_document ;
                        $finalStudent[0]->Nombre = $lastStudent->per_name ;
                        $finalStudent[0]->Apellido = $lastStudent->per_lastname ;
                        $finalStudent[0]->{'Correo Electronico'} = $lastStudent->use_mail ;
                        $finalStudent[0]->{'Nombre Carrera'} = $lastStudent->car_name ;
                        $finalStudent[0]->Promocion = $lastStudent->pro_name ;
                        array_push($students, $finalStudent[0]);
                    }
                }

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
                        'viewEnrollments.pro_name as Promoción',
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
                        'viewEnrollments.stu_enr_journey as Jornada',
                        'viewEnrollments.pro_name as Promoción',
                        'viewEnrollments.car_name as Nombre carrera',
                        'viewAssitances.bie_act_date as Fecha actividad',
                        'viewAssitances.bie_act_hour as Hora actividad',
                        'viewAssitances.stu_typ_name as Tipo estudiante'
                    )
                    ->join('viewEnrollments', 'viewAssitances.per_id', '=', 'viewEnrollments.per_id')
                    ->where('viewAssitances.bie_act_typ_id', '=', 9)
                    ->where('viewEnrollments.stu_enr_status', '=', 1)
                    ->get();

                foreach ($students as $student) {
                    if ($student->Jornada == 0) {
                        $student->Jornada = "diurno";
                    } elseif($student->Jornada == 1) {
                        $student->Jornada = "nocturno";
                    }else{
                        $student->Jornada = "N/A";
                    }

                }
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
                        'viewEnrollments.stu_enr_journey as Jornada',
                        'viewEnrollments.pro_name as Promoción',
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
                    foreach ($students as $student) {
                        if ($student->Jornada == 0) {
                            $student->Jornada = "diurno";
                        } elseif($student->Jornada == 1) {
                            $student->Jornada = "nocturno";
                        }else{
                            $student->Jornada = "N/A";
                        }

                    }
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
                        'viewEnrollments.stu_enr_journey as Jornada',
                        'viewEnrollments.pro_name as Promoción',
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

                    foreach ($students as $student) {
                        if ($student->Jornada == 0) {
                            $student->Jornada = "diurno";
                        } elseif($student->Jornada == 1) {
                            $student->Jornada = "nocturno";
                        }else{
                            $student->Jornada = "N/A";
                        }

                    }
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
                $student = db::table('students')->where('per_id', '=', $data->data)->first();
                if($student == null){
                    return "[]";
                }
                $lastStudent = Controller::lastEnrollments($student->stu_id);
                if($lastStudent == null){
                    return "[]";
                }
                $students = DB::table('viewAssitances as vs')
                ->join('viewEnrollments as ve', 'vs.per_id', '=', 've.per_id')
                ->join('promotions as pr', 've.pro_id', '=', 'pr.pro_id')
                ->select('vs.ass_id as Numero_asistencia', 'vs.per_id as Numero_persona', 'vs.per_name as Nombre', 'vs.per_lastname as Apellido', 'vs.per_document as Documento', 'vs.use_mail as Correo', 've.stu_enr_journey as Jornada', 'pr.pro_name as Promoción',  've.car_name as Carrera', 'vs.bie_act_date as Fecha_actividad', 'vs.bie_act_name as Actividad', 'vs.bie_act_hour as Hora_de_la_actividad', 'vs.stu_typ_name as Tipo_estudiante','vs.ass_status as Asistencia')
                ->where('ve.per_id', '=', $data->data)
                ->where('ve.car_name', '=', $lastStudent->car_name)
                ->get();
                foreach ($students as $stu) {
                    $tel = DB::table('telephones as tel')->where('tel.per_id', '=', $stu->Numero_persona)->select('tel_number as Teléfono', 'tel_description as Descripcion teléfono')->max('tel_number');
                    $stu->telefono = ($tel == null) ? 'No hay telefonos' : $tel;
                    switch($stu->Jornada){
                        case 0:
                            $stu->Jornada ='Diurno';
                        case 1:
                            $stu->Jornada ='Nocturno';
                    }
                    switch($stu->Asistencia){
                        case 0:
                            $stu->Asistencia ='No asistió';
                        case 1:
                            $stu->Asistencia ='Asistió';
                    }
                }
            return $students;
            case "2":
                $student = db::table('students')->where('per_id', '=', $data->data)->first();
                if($student == null){
                    return "[]";
                }
                $lastStudent = Controller::lastEnrollments($student->stu_id);
                if($lastStudent == null){
                    return "[]";
                }
                $students = DB::table('viewAssitances as vs')
                ->join('viewEnrollments as ve', 'vs.per_id', '=', 've.per_id')
                ->join('promotions as pr', 've.pro_id', '=', 'pr.pro_id')
                ->select('vs.ass_id as Numero_asistencia', 'vs.per_id as Numero_persona', 'vs.per_name as Nombre', 'vs.per_lastname as Apellido', 'vs.per_document as Documento', 'vs.use_mail as Correo', 've.stu_enr_journey as Jornada', 'pr.pro_name as Promoción',  've.car_name as Carrera', 'vs.bie_act_date as Fecha_actividad', 'vs.bie_act_name as Actividad', 'vs.bie_act_hour as Hora_de_la_actividad', 'vs.bie_act_typ_id as Numero_actividad','vs.stu_typ_name as Tipo_estudiante')
                ->where('vs.bie_act_typ_id', '=', 1)
                ->where('ve.per_id', '=', $data->data)
                ->where('ve.car_name', '=', $lastStudent->car_name)
                ->get();
                foreach ($students as $stu) {
                    $tel = DB::table('telephones as tel')->where('tel.per_id', '=', $stu->Numero_persona)->select('tel_number as Teléfono', 'tel_description as Descripcion teléfono')->max('tel_number');
                    $stu->telefono = ($tel == null) ? 'No hay telefonos' : $tel;
                    switch($stu->Jornada){
                        case 0:
                            $stu->Jornada ='Diurno';
                        case 1:
                            $stu->Jornada ='Nocturno';
                    }
                }
                return $students;
                break;
            case "3":
                $student = db::table('students')->where('per_id', '=', $data->data)->first();
                if($student == null){
                    return "[]";
                }
                $lastStudent = Controller::lastEnrollments($student->stu_id);
                if($lastStudent == null){
                    return "[]";
                }
                $students = DB::table('viewPermanences as vp')
                ->join('viewEnrollments as ve', 've.per_id', '=', 'vp.per_id')
                ->join('students as st', 'st.per_id', '=', 'vp.per_id')
                ->join('permanences as per', 'per.perm_id', '=', 'vp.perm_id')
                ->join('actions as a', 'a.act_id', '=', 'per.act_id')
                ->join('promotions as pr', 'pr.pro_id', '=', 've.pro_id')
                ->join('viewSolicitudes as vs','vs.per_id', '=', 'vp.per_id')
                ->select('ve.stu_typ_name as Tipo_de_estudiante','vp.per_id as Numero_persona', 'vp.act_name as Acción', 've.car_name as Carrera', 'pr.pro_name as Promoción',  've.stu_enr_journey as Jornada', 'vp.per_document as Documento', 'vp.perm_description as Observación','vp.per_name as Nombre', 'vp.per_lastname as Apellido', 've.use_mail as Correo_institucional', 'vp.sol_typ_name as Solicitud', 'vp.perm_date as Fecha_de_gestión', 'vp.rea_typ_name as Motivo_de_estado')
                ->where('vs.rea_typ_type', '=', 1)
                ->where('vs.per_id', '=', $data->data)
                ->where('ve.car_name', '=', $lastStudent->car_name)
                ->get();
                foreach ($students as $stu) {
                    $tel = DB::table('telephones as tel')->where('tel.per_id', '=', $stu->Numero_persona)->select('tel_number as Teléfono', 'tel_description as Descripcion teléfono')->max('tel_number');
                    $stu->telefono = ($tel == null) ? 'No hay telefonos' : $tel;
                    switch($stu->Jornada){
                        case 0:
                            $stu->Jornada ='Diurno';
                        case 1:
                            $stu->Jornada ='Nocturno';
                    }
                }
                return $students;
                break;
            case "4":
                $student = db::table('students')->where('per_id', '=', $data->data)->first();
                if($student == null){
                    return "[]";
                }
                $lastStudent = Controller::lastEnrollments($student->stu_id);
                if($lastStudent == null){
                    return "[]";
                }
                $students = DB::table('viewPermanences as vp')
                    ->join('viewEnrollments as ve', 've.per_id', '=', 'vp.per_id')
                    ->join('students as st', 'st.per_id', '=', 'vp.per_id')
                    ->join('permanences as per', 'per.perm_id', '=', 'vp.perm_id')
                    ->join('actions as a', 'a.act_id', '=', 'per.act_id')
                    ->join('promotions as pr', 'pr.pro_id', '=', 've.pro_id')
                    ->select('ve.stu_typ_name as Tipo_de_estudiante','vp.per_id as Numero_persona', 'vp.act_name as Acción', 've.car_name as Carrera', 'pr.pro_name as Promoción',  've.stu_enr_journey as Jornada', 'vp.per_document as Documento', 'vp.perm_description as Observación','vp.per_name as Nombre', 'vp.per_lastname as Apellido', 've.use_mail as Correo_institucional', 'vp.sol_typ_name as Solicitud', 'vp.perm_date as Fecha_de_gestión','a.act_name as Acción_de_permanencia', 'vp.rea_typ_name as Motivo_de_estado')
                    ->where('vp.per_id', '=', $data->data)
                    ->where('ve.car_name', '=', $lastStudent->car_name)
                    ->get();
                    foreach ($students as $stu) {
                        $tel = DB::table('telephones as tel')->where('tel.per_id', '=', $stu->Numero_persona)->select('tel_number as Teléfono', 'tel_description as Descripcion teléfono')->max('tel_number');
                        $stu->telefono = ($tel == null) ? 'No hay telefonos' : $tel;
                        switch($stu->Jornada){
                            case 0:
                                $stu->Jornada ='Diurno';
                            case 1:
                                $stu->Jornada ='Nocturno';
                        }
                    }
                return $students;
                break;
            case "5":
                $student = db::table('students')->where('per_id', '=', $data->data)->first();
                if($student == null){
                    return "[]";
                }
                $lastStudent = Controller::lastEnrollments($student->stu_id);
                if($lastStudent == null){
                    return "[]";
                }
                $students = DB::table('gym_assistances as gy')
                ->join('viewEnrollments as ve', 'gy.per_id', '=', 've.per_id')
                ->join('promotions as pr', 've.pro_id', '=', 'pr.pro_id')
                ->select('gy.gym_ass_id as Numero_asistencia_gimnasio', 've.stu_enr_journey as Jornada', 've.per_id as Numero_persona','ve.car_name as Carrera', 'pr.pro_name as Promoción',  've.per_document as Documento', 've.per_name as Nombre', 've.per_lastname as Apellido', 'gy.gym_ass_date as Fecha_de_asistencia')
                ->where('ve.per_id', '=', $data->data)
                ->where('ve.car_name', '=', $lastStudent->car_name)
                ->distinct('gy.gym_ass_id')
                ->get();
                foreach ($students as $stu) {
                    $tel = DB::table('telephones as tel')->where('tel.per_id', '=', $stu->Numero_persona)->select('tel_number as Teléfono', 'tel_description as Descripcion teléfono')->max('tel_number');
                    $stu->telefono = ($tel == null) ? 'No hay telefonos' : $tel;
                    switch($stu->Jornada){
                        case 0:
                            $stu->Jornada ='Diurno';
                        case 1:
                            $stu->Jornada ='Nocturno';
                    }
                }
                return $students;
                break;
            case "6":
                $student = db::table('students')->where('per_id', '=', $data->data)->first();
                if($student == null){
                    return "[]";
                }
                $lastStudent = Controller::lastEnrollments($student->stu_id);
                if($lastStudent == null){
                    return "[]";
                }
                $students = DB::table('viewStudents as vt')
                    ->join('consultations as co', 'vt.per_id', '=', 'co.per_id')
                    ->join('viewEnrollments as ve', 'vt.per_id', '=', 've.per_id')
                    ->join('promotions as pr', 've.pro_id', '=', 'pr.pro_id')
                    ->join('enfermeria_inscriptions as en', 'en.per_id', '=', 'vt.per_id')
                    ->select('vt.stu_typ_name as Tipo_de_estudiante', 'vt.stu_id as Numero_estudiante', 'vt.per_name as Nombre', 'vt.per_lastname as Apellido', 'vt.per_document as Documento', 'vt.per_rh as Grupo_sanguíneo', 'vt.per_birthdate as Fecha_de_nacimiento', 'vt.per_direction as Direccion', 'vt.eps_name as EPS', 've.stu_enr_journey as Jornada', 'pr.pro_name as Promoción',  've.car_name as Carrera', 'co.cons_reason as Razón_consulta', 'co.cons_description as Descripción_consulta', 'co.cons_date as Fecha_consulta','en.enf_ins_height as Altura','en.enf_ins_weight as Peso','en.enf_ins_vaccination as Vacunas')
                    ->where('ve.per_id', '=', $data->data)
                    ->where('ve.car_name', '=', $lastStudent->car_name)
                    ->where('ve.stu_enr_status', '=', 1)
                    ->get();

                    foreach($students as $stu){
                    switch($stu->Jornada){
                        case 0:
                            $stu->Jornada ='Diurno';
                        case 1:
                            $stu->Jornada ='Nocturno';
                    }

                    }
                return $students;
                break;
            case "7":
                $students = DB::table('viewPermanences as vp')
                    ->join('viewEnrollments as ve', 've.per_id', '=', 'vp.per_id')
                    ->join('students as st', 'st.per_id', '=', 'vp.per_id')
                    ->join('permanences as per', 'per.perm_id', '=', 'vp.perm_id')
                    ->join('actions as a', 'a.act_id', '=', 'per.act_id')
                    ->join('promotions as pr', 'pr.pro_id', '=', 've.pro_id')
                    ->select('vp.per_id as Numero_persona', 'vp.act_name as Acción', 've.car_name as Carrera', 'pr.pro_name as Promoción',  've.stu_enr_journey as Jornada', 'vp.per_document as Documento', 'vp.per_name as Nombre', 'vp.per_lastname as Apellido', 've.use_mail as Correo_institucional', 'vp.sol_typ_name as Solicitud', 'vp.perm_date as Fecha_de_gestión', 'vp.rea_typ_name as Motivo_de_estado')
                    ->where('a.act_id', '=', $data->data)
                    ->get();
                foreach ($students as $stu) {
                    $tel = DB::table('telephones as tel')->where('tel.per_id', '=', $stu->Numero_persona)->select('tel_number as Teléfono', 'tel_description as Descripcion teléfono')->max('tel_number');
                    $stu->telefono = ($tel == null) ? 'No hay telefonos' : $tel;
                    switch($stu->Jornada){
                        case 0:
                            $stu->Jornada ='Diurno';
                        case 1:
                            $stu->Jornada ='Nocturno';
                    }
                }
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

    public static function reportStudent($data){
        $information = array();
        for ($i=1; $i < 7 ; $i++){
            $data->merge(['option'=>$i]);
            $info = Reports::select($data);
           switch ($i) {
            case 1:
                $information['assistances'] = $info;
                break;
            case 2:
                $information['Conversatorios'] = $info;
                break;
            case 3:
                $information['Orientacion'] = $info;
                break;
            case 4:
                $information['Permaniencias'] = $info;
                break;
            case 5:
                $information['Gimnasio'] = $info;
                break;
            case 6:
                $information['Salud'] = $info;
                break;
            default:
                break;
           }
        }
        return $information;
    }
}
