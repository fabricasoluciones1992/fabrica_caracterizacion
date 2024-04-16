<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reports extends Model
{
    use HasFactory;

    public static function index($data) {
        switch ($data->table) {
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
}
