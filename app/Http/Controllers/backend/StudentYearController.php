<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class StudentYearController extends Controller
{
    public function index()
    {
        try {
            $year = DB::table('student_years')->select('id','name')->get();
            if (count($year) > 0){
                return apiResponse($year,'');
            }
            else{
                return apiResponse(null,'No data found');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage(),$e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $year = DB::table('student_years')->where('id',$id)->first();
            if ($year != null){
                return apiResponse($year,'');
            }
            else{
                return apiResponse(null,'No data found');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage(),$e->getCode());
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'name'=>'required|unique:student_years,name'
        ]);

        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $year = DB::table('student_years')->insert([
               'name'=> $request->name
            ]);
            if ($year > 0){
                return apiResponse(null,'Year insert successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage(),$e->getCode());
        }

    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'name'=>'required|unique:student_years,name,'.$request->id
        ]);

        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $year = DB::table('student_years')->where('id',$request->id)->update([
                'name'=> $request->name
            ]);
            if ($year > 0){
                return apiResponse(null,'Year update successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage(),$e->getCode());
        }

    }

    public function destroy($id)
    {
        try {
            $year = DB::table('student_years')->where('id',$id)->delete();
            if ($year > 0){
                return apiResponse(null,'Year delete successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage(),$e->getCode());
        }
    }
}
