<?php
namespace App\Http\Controllers;
use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
class DiseasesController extends Controller
{
    public function index($proj_id, $use_id)
    {
        $diseases = Disease::getbienestar_news();
        return response()->json([
                'status' => true,
                'data' => $diseases
            ], 200);
        
    }
    public function store($proj_id, $use_id, Request $request)
    {
        $rules = [
            'dis_name' => 'required|string|min:1|max:50|unique:diseases|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $disease = new Disease(($request->input()));
            $disease->save();
            Controller::NewRegisterTrigger("An insertion was made in the Diseases table'$disease->dis_id'", 3, $use_id);
            $id = $disease->dis_id;
            $bienestar_news=DiseasesController::Getbienestar_news($id);
            return response()->json([
                'status' => true,
                'message' => "The Disease: " . $disease->dis_name . " has been created.",
                'data' => $bienestar_news

            ], 200);
        }
    }
    public function Getbienestar_news($id)
{
    $dis_id = $id;
    $bienestar_news = DB::table('bienestar_news')
        ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
        ->select('bie_new_date', 'persons.per_name')
        ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the Diseases table\'$dis_id\''")
        ->get();

    if ($bienestar_news->count() > 0) {
        return $bienestar_news[0];
    } else {
        return null;
    }
}
    public function show($proj_id, $use_id, $id)
    {
        $disease = Disease::find($id);
        $bienestar_news=DiseasesController::Getbienestar_news($id);

        if ($disease == null) {
            return response()->json([
                'status' => False,
                'data' => ['message' => 'The disease requested not found'],
            ], 400);
        } else {
            $disease->new_date = $bienestar_news->bie_new_date;
            $disease->createdBy = $bienestar_news->per_name;
            return response()->json([
                'status' => true,
                'data' => $disease
            ]);
        }
    }
    public function update($proj_id, $use_id, Request $request, $id)
    {
        $disease = Disease::find($id);
        if ($disease == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The disease requested not found'],
            ], 400);
        } else {
            $rules = [
                'dis_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->dis_name, 'Disease', 'dis_name', 'dis_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                    'status' => False,
                    'message' => $msg
                ]);
            } else {
                $disease = Disease::find($id);
                $disease->dis_name = $request->dis_name;
                $disease->save();
                Controller::NewRegisterTrigger("An update was made in the diseases table: id->$id", 4, $use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The Disease: " . $disease->dis_name . " has been update."
                ], 200);
            }
        }
    }
    public function destroy(Disease $Disease)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
        ], 400);
    }
}