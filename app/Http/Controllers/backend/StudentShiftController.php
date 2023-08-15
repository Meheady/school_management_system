<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class StudentShiftController extends Controller
{
    public function index()
    {
        try {
            $group = DB::table('student_shifts')->select('id','name')->get();
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
            $group = DB::table('student_shifts')->where('id', $id)->select('id','name')->first();
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
            'name'=> 'required|unique:student_shifts,name'
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $insertGroup = DB::table('student_shifts')->insert([
                'name'=> $request->name,
            ]);
            if ($insertGroup > 0){
                return apiResponse(null,'Shift insert successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required|unique:student_shifts,name,'.$request->id
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $updateGroup = DB::table('student_shifts')->where('id', $request->id)->update([
                'name'=> $request->name,
            ]);
            if ($updateGroup > 0){
                return apiResponse(null,'Shift update successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function destroy($id)
    {

        try {
            $deleteGroup = DB::table('student_shifts')->where('id', $id)->delete();
            if ($deleteGroup > 0){
                return apiResponse(null,'Shift delete successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }
}
