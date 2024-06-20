<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use App\Models\BienestarActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Rule;
class AssistancesController extends Controller
{
    public function index()
    {

        $assistances = Assistance::select();

        return response()->json([
            'status' => true,
            'data' => $assistances
        ],200);


    }
    public function store(Request $request)
{

    $rules = [
        'use_id' =>'required|exists:users|integer',
        'bie_act_id' =>'required|exists:bienestar_activities|integer',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors()->all()
        ]);
    }

    $student = DB::table('students')->where('per_id', $request->use_id)->first();

    if (!$student) {
        return response()->json([
            'status' => false,
            'message' => 'The user who is registering is not a student'
        ]);
    }

    $activity = BienestarActivity::find($request->bie_act_id);

    if (!$activity) {
        return response()->json([
            'status' => false,
            'message' => 'The activity does not exist'
        ]);
    }

    $currentQuotas = $activity->countQuotas($request->bie_act_id);

    if ($currentQuotas >= $activity->bie_act_quotas) {
        return response()->json([
            'status' => false,
            'message' => 'The activity has reached its maximum capacity'
        ]);
    }

    $existingAssistance = DB::table('assistances')
                            ->where('bie_act_id', $request->bie_act_id)
                            ->where('stu_id', $student->stu_id)
                            ->first();

    if ($existingAssistance) {
        return response()->json([
            'status' => false,
            'message' => 'The student is already registered for this activity'
        ]);
    }

    $currentDate = now()->toDateString();
    $request->merge(['ass_date' => $currentDate]);

    $newAssistanceId = DB::table('assistances')->insertGetId([
        'bie_act_id' => $request->bie_act_id,
        'stu_id' => $student->stu_id,
        'ass_date' => $request->ass_date,
        'ass_status' => 0,
        'ass_reg_status' => 1,
    ]);

    Controller::NewRegisterTrigger("An insertion was made in the assistences table '$newAssistanceId'", 3, $request->use_id);

    return response()->json([
        'status' => true,
        'message' => "The assistance has been created successfully."
    ], 200);
}

    public function show($id)
    {

        $assistances =  Assistance::search($id);


        if ($assistances == null) {

            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched assistance was not found']
            ],400);
        }else{

            return response()->json([
                'status' => true,
                'data' => $assistances
            ]);
        }
    }
    public function update(Request $request, $id)
{
    $activity = BienestarActivity::find($request->bie_act_id);
    $currentQuotas = $activity->countQuotas($request->bie_act_id);
    $assistances = Assistance::find($id);

    if ($request->acc_administrator == 1) {
        if ($assistances == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched assistance was not found']
            ], 400);
        } else {
            $rules = [
                'stu_id' => 'required|integer|exists:students|min:1',
                'ass_status' => 'required|integer',
                'bie_act_id' => 'required|integer|exists:bienestar_activities|min:1',
            ];
            $validator = Validator::make($request->input(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()
                ]);
            }

            if ($currentQuotas >= $activity->bie_act_quotas) {
                return response()->json([
                    'status' => false,
                    'message' => 'The activity has reached its maximum capacity'
                ]);
            }

            $existingAssistance = DB::table('assistances')
                ->where('bie_act_id', $request->bie_act_id)
                ->where('stu_id', $request->stu_id)
                ->where('ass_status', $request->ass_status)
                ->first();

            if ($existingAssistance) {
                return response()->json([
                    'status' => false,
                    'message' => 'The student is already registered for this activity'
                ]);
            }

            $assistances->ass_date = now()->toDateString();
            $assistances->ass_status = $request->ass_status;
            $assistances->stu_id = $request->stu_id;
            $assistances->bie_act_id = $request->bie_act_id;
            $assistances->save();
            Controller::NewRegisterTrigger("An update was made in assistances table", 4, $request->use_id);

            return response()->json([
                'status' => true,
                'message' => "The assistance has been updated."
            ], 200);
        }
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Access denied. This action can only be performed by active administrators.'
        ], 403);
    }
}


    public function destroy(Request $request,$id)
    {
        $assistances = assistance::find($id);
                $newAss=($assistances->ass_status==1) ? 0:1;
                $assistances->ass_status =$newAss;
                $assistances->save();
                Controller::NewRegisterTrigger("An change status was made in the actions table",2,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested assistances has been change successfully'
                ]);


    }
    public function destroyAR(Request $request, $id)
{
    $assistances = Assistance::find($id);

    if ($assistances) {
        $newAsg = ($assistances->ass_reg_status == 1) ? 0 : 1;

        if ($newAsg == 1) {
            $activity = BienestarActivity::find($assistances->bie_act_id);
            $currentQuotas = $activity->countQuotas($assistances->bie_act_id);

            if ($currentQuotas >= $activity->bie_act_quotas) {
                return response()->json([
                    'status' => false,
                    'message' => 'The activity has reached its maximum capacity'
                ]);
            }
        }

        $assistances->ass_reg_status = $newAsg;
        $assistances->save();

        Controller::NewRegisterTrigger("A change status was made in the bienestar activity type table", 2, $request->use_id);

        return response()->json([
            'status' => true,
            'message' => 'The requested assistance register has been changed successfully'
        ]);
    } else {
        return response()->json([
            'status' => false,
            'message' => 'The requested assistance was not found'
        ], 404);
    }
}


    public function uploadFile(Request $request)
    {
        //debe buscar en persons por per_document = documento del archivo. per_id agregar al request y depues llamar al store dentro del foreach y enviar el request
        $count = 0;
        $responses[] = [];
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $file->storeAs('csv', $file->getClientOriginalName());

            $csvData = array_map('str_getcsv', file($file->path()));

            foreach ($csvData as $index => $row) {
                if ($index == 0 && !is_numeric($row[0])) {
                    continue;
                }

                $document = trim($row[0]);

                // Verificar si el documento es un número válido
                if (preg_match('/[!@#$%^&*(),.?":{}|<>]/', $row[0])) {
                    return response()->json([
                        'status' => false,
                        'message' => "El archivo tiene datos invalidos."
                     ], 200);
                }

                $person = db::table('persons')->where('per_document', $row)->first();
                if($person == null){
                    $responses[] = [
                        "Datos invalidos: ".$row[0]
                    ];
                    continue;

                }


                // $assistance = new Assistance();
                // $assistance->ass_date = date('Y-m-d');
                // $assistance->ass_status = 1;
                // $assistance->stu_id = intval($row[0]);
                // $assistance->bie_act_id = $request->bie_act_id;
                // $assistance->ass_reg_status = 1;
                // $assistance->save();

                $request->merge(['use_id' => $person->use_id]);
                $assistance = AssistancesController::store($request);
                $data = json_decode($assistance->getContent(), true);
                $status = $data;
                if($status["status"] == false){
                    $responses[] = [
                     $data["message"].'. Id: '.$person->per_document
                    ];
                }else{
                    $count = $count + 1;
                };

            }
            $responses[0] = [
                "status" => true,
                "message" => "The file has been uploaded successfully with ".$count." new registers"
            ];
            return response()->json([
               'status' => true,
               'message' => $responses
            ], 200);




        }else{
            return response()->json(['error' => 'No CSV file found in request'], 400);
        }

    }
}
