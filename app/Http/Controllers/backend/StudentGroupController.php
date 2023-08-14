<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentGroupController extends Controller
{
    public function index()
    {
        try {
            $group = DB::table('student_groups')->select('id','name')->get();
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
            $group = DB::table('student_groups')->where('id', $id)->select('id','name')->first();
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
           'name'=> 'required|unique:student_groups,name'
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $insertGroup = DB::table('student_groups')->insert([
               'name'=> $request->name,
            ]);
            if ($insertGroup > 0){
                return apiResponse(null,'Group insert successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required|unique:student_groups,name,'.$request->id
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $updateGroup = DB::table('student_groups')->where('id', $request->id)->update([
                'name'=> $request->name,
            ]);
            if ($updateGroup > 0){
                return apiResponse(null,'Group update successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function destroy($id)
    {

        try {
            $deleteGroup = DB::table('student_groups')->where('id', $id)->delete();
            if ($deleteGroup > 0){
                return apiResponse(null,'Group delete successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }
}
