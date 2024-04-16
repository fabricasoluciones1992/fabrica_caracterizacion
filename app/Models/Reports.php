<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reports extends Model
{
    use HasFactory;

    public static function index($data) {
        switch ($data->option) {
            case "1":
                $students = DB::select("SELECT car_name,pro_name,stu_journey,per_document,stu_code,per_name,per_lastname, use_mail, tel_number FROM ViewStudents where per_typ_id = $data->data");
                return $students;
                break;
            case "2":
                $students = DB::select("SELECT car_name,pro_name,stu_journey,per_document,stu_code,per_name,per_lastname, use_mail, tel_number FROM ViewStudents where car_id = $data->data");
                return $students;
                break;
            case "3":
                return ;
                break;
            case "4":
                return ;
                break;
            case "5":
                return ;
                break;
            case "6":
                return ;
                break;
            default:
                # code...
                break;
        }
    }

    public static function select($data){
        switch ($data->option) {
            case "1":
                if($data->code === null || $data->code === 0){
                    $students = DB::select("SELECT stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE per_document = $data->document");
                }elseif($data->document === null || $data->document === 0){
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code");
                }else{
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code AND per_document = $data->document");
                }
                return $students;
                break;
            case "2":
                if($data->code === null || $data->code === 0){
                    $students = DB::select("SELECT stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE per_document = $data->document");
                }elseif($data->document === null || $data->document === 0){
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code");
                }else{
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code AND per_document = $data->document");
                }  
                return $students;
                break;
            case "3":
                if($data->code === null || $data->code === 0){
                    $students = DB::select("SELECT stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE per_document = $data->document");
                }elseif($data->document === null || $data->document === 0){
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code");
                }else{
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code AND per_document = $data->document");
                } 
                return $students;
                break;
            case "4":
                if($data->code === null || $data->code === 0){
                    $students = DB::select("SELECT stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE per_document = $data->document");
                }elseif($data->document === null || $data->document === 0){
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code");
                }else{
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code AND per_document = $data->document");
                } 
                return $students;
                break;
            case "5":
                if($data->code === null || $data->code === 0){
                    $students = DB::select("SELECT stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE per_document = $data->document");
                }elseif($data->document === null || $data->document === 0){
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code");
                }else{
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code AND per_document = $data->document");
                } 
                return $students;
                break;
            case "6":
                if($data->code === null || $data->code === 0){
                    $students = DB::select("SELECT stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE per_document = $data->document");
                }elseif($data->document === null || $data->document === 0){
                    $students = DB::select("SELECT  stu_id, car_name, pro_name, pro_group, stu_journey, per_document, stu_code, per_name, tel_number, use_mail, per_typ_name
                    FROM ViewStudents WHERE stu_code = $data->code");
                }else{
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
