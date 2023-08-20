<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class ExamTypeController extends Controller
{
    public function index()
    {
        try {
            $group = DB::table('exam_types')->select('id','exam_name')->get();
            if ($group != null){
                return apiResponse($group);
            }
            return apiResponse(null,'No data found');
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $group = DB::table('exam_types')->where('id', $id)->select('id','exam_name')->first();
            if ($group != null){
                return apiResponse($group);
            }
            return apiResponse(null,'No data found');
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'examName'=> 'required|unique:exam_types,exam_name'
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $insertGroup = DB::table('exam_types')->insert([
                'exam_name'=> $request->examName,
            ]);
            if ($insertGroup > 0){
                return apiResponse(null,'Exam Type insert successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'examName'=> 'required|unique:exam_types,exam_name,'.$request->id
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $updateGroup = DB::table('exam_types')->where('id', $request->id)->update([
                'exam_name'=> $request->examName,
            ]);
            if ($updateGroup > 0){
                return apiResponse(null,'Exam type update successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function destroy($id)
    {

        try {
            $deleteGroup = DB::table('exam_types')->where('id', $id)->delete();
            if ($deleteGroup > 0){
                return apiResponse(null,'Exam Types delete successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }
}
