<?php

namespace App\Http\Controllers\backend\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentRegController extends Controller
{

    protected function uploadImg ($image): string
    {
        $imgName = time().'.'.$image->getClientOriginalExtension();
        $imgPath = 'assets/student-photo/';
        $image->move($imgPath,$imgName);
        return $imgPath.$imgName;
    }

    public function search(Request $request){

        $year = $request->year;
        $class = $request->class;

        $searchStudent = DB::table('student_registrations AS sr')
            ->join('users AS u','u.id','=','sr.student_id')
            ->join('student_years AS yr','yr.id','=','sr.year_id')
            ->join('student_classes AS cls','cls.id','=','sr.class_id')
            ->select('u.name as student_name','yr.name as student_year','cls.name as student_class','u.profile_photo_path as student_image')
            ->where('sr.class_id',$class)->orWhere('sr.year_id',$year)->get();
        return $searchStudent;

    }

    public function store(Request $request)
    {

        try {
            DB::transaction(function () use ($request){
                $studentYear = DB::table('student_years')->select('name')->where('id',$request->yearId)->first();
                $student = DB::table('users')->where('usertype', 'student')
                    ->orderBy('id','desc')
                    ->first();

                if ($student == null){
                    $firstReg = 0;
                    $studentId = $firstReg + 1;

                }
                else{
                    $studentId = $student->id + 1;
                }
                if ($studentId < 10) $idNo = '000'.$studentId;
                if ($studentId < 100) $idNo = '00'.$studentId;
                if ($studentId < 1000) $idNo = '0'.$studentId;

                $finalIdNo = $studentYear->name.$idNo;
                $code = rand(0000,9999);

                $insertStudent = DB::table('users')->insertGetId([
                    'id_no'=>$finalIdNo,
                    'code'=>$code,
                    'password'=>bcrypt($code),
                    'usertype'=>'student',
                    'name'=>$request->studentName,
                    'father_name'=>$request->fatherName,
                    'mother_name'=>$request->motherName,
                    'religion'=>$request->religion,
                    'gender'=>$request->gender,
                    'address'=>$request->address,
                    'mobile'=>$request->mobileNumber,
                    'dob'=>$request->DOB,
                    'profile_photo_path'=>$this->uploadImg($request->file('image')),
                ]);

                $registrationStudent = DB::table('student_registrations')->insertGetId([
                    'student_id'=>$insertStudent,
                    'class_id'=>$request->classId,
                    'year_id'=>$request->yearId,
                    'group_id'=>$request->groupId,
                    'shift_id'=>$request->shiftId,
                ]);

                $discountStudent = DB::table('discount_students')->insertGetId([
                    'student_reg_id'=>$registrationStudent,
                    'fee_category_id'=> $request->discountFeeCategory,
                    'discount'=>$request->discount,
                ]);
                return apiResponse(null,'Student registration complete');
            });
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }

    }
}
