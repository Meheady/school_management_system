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

        try {
            $searchStudent = DB::table('student_registrations AS sr')
                ->join('users AS u','u.id','=','sr.student_id')
                ->join('student_years AS yr','yr.id','=','sr.year_id')
                ->join('student_classes AS cls','cls.id','=','sr.class_id')
                ->select('sr.student_id','u.name as student_name','yr.name as student_year','cls.name as student_class','u.profile_photo_path as student_image')
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
    public function show ($student_id){

        try {
            $showStudent = DB::table('student_registrations AS sr')
                ->join('users AS u','u.id','=','sr.student_id')
                ->join('discount_students AS ds','sr.id','=','ds.student_reg_id')
                ->select('sr.*','u.*','ds.*')
                ->where('sr.student_id',$student_id)->first();
            if ($showStudent != null){
                return apiResponse($showStudent);
            }
            return apiResponse(null,'No data found');
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }

    }
    public function store(Request $request)
    {

        try {
            return DB::transaction(function () use ($request){
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

    public function promotion(Request $request)
    {

        try {
            return DB::transaction(function () use ($request){

                $registrationStudent = DB::table('student_registrations')->insertGetId([
                    'student_id'=>$request->studentId,
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
                return apiResponse(null,'Student promote successfully');
            });
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }

    }

    public function update(Request $request)
    {

        try {
            return DB::transaction(function () use ($request){

                if ($request->hasFile('image')){
                    $userData = DB::table('users')->where('id',$request->studentId)->first();

                    if (file_exists($userData->profile_photo_path)){
                        unlink($userData->profile_photo_path);
                    }
                    $updateStudent = DB::table('users')->where('id',$request->studentId)->update([
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
                }
                else{
                    $updateStudent = DB::table('users')->where('id',$request->studentId)->update([
                        'usertype'=>'student',
                        'name'=>$request->studentName,
                        'father_name'=>$request->fatherName,
                        'mother_name'=>$request->motherName,
                        'religion'=>$request->religion,
                        'gender'=>$request->gender,
                        'address'=>$request->address,
                        'mobile'=>$request->mobileNumber,
                        'dob'=>$request->DOB,
                    ]);
                }

                $registerStudent = DB::table('student_registrations')->where('student_id',$request->studentId)->first();

                $updateRegistrationStudent = DB::table('student_registrations')->where('student_id',$request->studentId)->update([
                    'class_id'=>$request->classId,
                    'year_id'=>$request->yearId,
                    'group_id'=>$request->groupId,
                    'shift_id'=>$request->shiftId,
                ]);

                $updateDiscountStudent = DB::table('discount_students')->where('student_reg_id',$registerStudent->id)->update([
                    'fee_category_id'=> $request->discountFeeCategory,
                    'discount'=>$request->discount,
                ]);
                return apiResponse(null,'Student registration updated');
            });
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }

    }
}
