<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;


class SchoolSubjectController extends Controller
{
    public function index()
    {
        try {
            $group = DB::table('school_subjects')->select('id','subject_name')->get();
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
            $group = DB::table('school_subjects')->where('id', $id)->select('id','subject_name')->first();
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
            'name'=> 'required|unique:school_subjects,subject_name'
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $insertGroup = DB::table('school_subjects')->insert([
                'subject_name'=> $request->name,
            ]);
            if ($insertGroup > 0){
                return apiResponse(null,'Subject insert successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required|unique:school_subjects,subject_name,'.$request->id
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $updateGroup = DB::table('school_subjects')->where('id', $request->id)->update([
                'subject_name'=> $request->name,
            ]);
            if ($updateGroup > 0){
                return apiResponse(null,'Subject update successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function destroy($id)
    {

        try {
            $deleteGroup = DB::table('school_subjects')->where('id', $id)->delete();
            if ($deleteGroup > 0){
                return apiResponse(null,'Subject delete successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }
}
