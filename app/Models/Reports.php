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

                //$students = DB::select("SELECT car_name,pro_name,stu_journey,per_document,stu_code,per_name,per_lastname, use_mail, tel_number FROM ViewStudents where per_typ_id = $data->data");
                $students = DB::table('ViewStudents')
                    ->select('per_typ_name', 'car_name', 'pro_name', 'stu_journey', 'per_document', 'per_name', 'per_lastname', 'use_mail', 'tel_number')
                    ->where('per_typ_id', '=', $data->data)
                    ->get();
                return $students;
                break;
            case "2":

                // $students = DB::select("SELECT car_name,pro_name,stu_journey,per_document,stu_code,per_name,per_lastname, use_mail, tel_number FROM ViewStudents where car_id = $data->data");
                $students = DB::table('ViewStudents')
                    ->select('per_typ_name', 'car_name', 'pro_name', 'stu_journey', 'per_document', 'per_name', 'per_lastname', 'use_mail', 'tel_number')
                    ->where('car_id', '=', $data->data)
                    ->get();
                return $students;
                break;
            case "3":

                $students = DB::table('ViewStudents')->join('viewHistorialConsultas AS hc', 'hc.per_document', '=', 'ViewStudents.per_document')
                    ->select('per_typ_name', 'car_name', 'pro_name', 'stu_journey', 'hc.per_document', 'hc.per_name', 'hc.per_lastname', 'use_mail', 'tel_number')
                    ->where('hc.cons_id', '=', $data->data)
                    ->get();
                return $students;

                break;
            case "4":
              
                $students = DB::table('ViewStudents')->rightJoin('Vista_Actividades_Bienestar_Estudiante AS hc', 'hc.stu_id', '=', 'ViewStudents.stu_id')
                    ->select('per_typ_name', 'hc.car_name', 'hc.pro_name', 'stu_journey', 'per_document', 'hc.stu_code', 'hc.per_name', 'hc.per_lastname', 'use_mail', 'tel_number')
                    ->where('hc.stu_id', '=', $data->data)
                    ->get();
                return $students;
                break;
            case "5":
                $students = DB::table('ViewStudents')->rightJoin('Vista_Actividades_Bienestar_Estudiante AS hc', 'hc.stu_id', '=', 'ViewStudents.stu_id')
                    ->select('per_typ_name', 'hc.car_name', 'hc.pro_name', 'stu_journey', 'per_document', 'hc.stu_code', 'hc.per_name', 'hc.per_lastname', 'use_mail', 'tel_number')
                    ->where('hc.stu_id', '=', $data->data)
                    ->get();
                return $students;
                break;
            case "6";
                $students = DB::table('ViewStudents')->join('document_types as dt','dt.doc_typ_id','=','ViewStudents.doc_typ_id')
                    ->select( 'ViewStudents.per_typ_name', 'ViewStudents.car_name', 'ViewStudents.pro_name', 'ViewStudents.stu_journey', 'ViewStudents.per_document', 'ViewStudents.stu_code', 'ViewStudents.per_name', 'ViewStudents.doc_typ_id','dt.doc_typ_name','ViewStudents.per_lastname','ViewStudents.use_mail', 'ViewStudents.tel_number', 'ViewStudents.eps_name', 'ViewStudents.per_rh')
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
                if ($data->code === null || $data->code === 0) {
                    $students = DB::select("SELECT stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE per_document = $data->document");
                } elseif ($data->document === null || $data->document === 0) {
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code");
                } else {
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code AND per_document = $data->document");
                }
                return $students;
                break;
            case "2":
                if ($data->code === null || $data->code === 0) {
                    $students = DB::select("SELECT stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE per_document = $data->document");
                } elseif ($data->document === null || $data->document === 0) {
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code");
                } else {
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code AND per_document = $data->document");
                }
                return $students;
                break;
            case "3":
                if ($data->code === null || $data->code === 0) {
                    $students = DB::select("SELECT stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE per_document = $data->document");
                } elseif ($data->document === null || $data->document === 0) {
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code");
                } else {
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code AND per_document = $data->document");
                }
                return $students;
                break;
            case "4":
                if ($data->code === null || $data->code === 0) {
                    $students = DB::select("SELECT stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE per_document = $data->document");
                } elseif ($data->document === null || $data->document === 0) {
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code");
                } else {
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code AND per_document = $data->document");
                }
                return $students;
                break;
            case "5":
                if ($data->code === null || $data->code === 0) {
                    $students = DB::select("SELECT stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE per_document = $data->document");
                } elseif ($data->document === null || $data->document === 0) {
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code");
                } else {
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code AND per_document = $data->document");
                }
                return $students;
                break;
            case "6":
                if ($data->code === null || $data->code === 0) {
                    $students = DB::select("SELECT stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE per_document = $data->document");
                } elseif ($data->document === null || $data->document === 0) {
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code");
                } else {
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code AND per_document = $data->document");
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
}
