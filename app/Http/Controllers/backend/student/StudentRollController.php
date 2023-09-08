<?php

namespace App\Http\Controllers\backend\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentRollController extends Controller
{
    public function search(Request $request)
    {
        $year = $request->year;
        $class = $request->class;

        try {
            $searchStudent = DB::table('student_registrations AS sr')
                ->join('users AS u','u.id','=','sr.student_id')
                ->join('student_years AS yr','yr.id','=','sr.year_id')
                ->join('student_classes AS cls','cls.id','=','sr.class_id')
                ->select('sr.id','sr.student_id','sr.roll as roll','u.name as student_name','yr.name as student_year','cls.name as student_class','u.profile_photo_path as student_image')
                ->where('sr.class_id',$class)->where('sr.year_id',$year)->get();
            if ($searchStudent != null){
                return apiResponse($searchStudent);
            }
            return apiResponse(null,'No data found');
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $year = $request->year;
            $class = $request->class;
            $countData = count($request->id);

            if ($countData > 0){
                foreach ($request->roll as $index=>$item){
                    $updateRoll = DB::table('student_registrations')
                        ->where('class_id',$class)->where('year_id',$year)
                        ->where('id',$request->id[$index])
                        ->update([
                           'roll'=> $request->roll[$index]
                        ]);
                }
                return apiResponse(null,'Role update successfully');
            }
            else{
                return apiError('Student not found');
            }
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }
}
