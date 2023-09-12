<?php

namespace App\Http\Controllers\backend\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeRegController extends Controller
{

    protected function uploadImg ($image): string
    {
        $imgName = time().'.'.$image->getClientOriginalExtension();
        $imgPath = 'assets/employee-photo/';
        $image->move($imgPath,$imgName);
        return $imgPath.$imgName;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $employees = DB::table('users as us')
                ->join('designations as des','des.id','=','us.designation_id')
                ->select('us.id','us.name','us.id_no','us.code','us.gender','us.salary','us.join_date','us.profile_photo_path','des.name as designation')
                ->where('usertype','employee')->get();
            if ($employees !=null){
                return apiResponse($employees);
            }
            else{
                return apiResponse(null, 'No data found');
            }
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }


    public function store(Request $request)
    {

        try {

            return DB::transaction(function () use ($request){
                $empJoinYear = date('Y',strtotime($request->joiningDate));
                $employee = DB::table('users')
                    ->where('usertype','employee')
                    ->orderBy('id','desc')->first();

                if ($employee == null){
                    $firstReg = 0;
                    $empId = $firstReg + 1;
                }
                else{
                    $empId = $employee->id + 1;
                }

                if ($empId < 10) $idNo = '000'.$empId;
                if ($empId < 100) $idNo = '00'.$empId;
                if ($empId < 1000) $idNo = '0'.$empId;

                $finalIdNo = $empJoinYear.$idNo;
                $code = rand(0000,9999);

                $insertEmployee = DB::table('users')->insertGetId([
                    'id_no'=>$finalIdNo,
                    'code'=>$code,
                    'password'=>bcrypt($code),
                    'usertype'=>'employee',
                    'name'=>$request->name,
                    'father_name'=>$request->fatherName,
                    'mother_name'=>$request->motherName,
                    'religion'=>$request->religion,
                    'gender'=>$request->gender,
                    'address'=>$request->address,
                    'mobile'=>$request->mobile,
                    'dob'=>$request->DOB,
                    'email'=>$request->email,
                    'join_date'=>$request->joiningDate,
                    'designation_id'=>$request->designation,
                    'salary'=>$request->salary,
                    'profile_photo_path'=>$this->uploadImg($request->file('profileImage')),
                ]);
                $employeeSalary = DB::table('employee_salary_logs')->insertGetId([
                    'employee_id'=>$insertEmployee,
                    'present_salary'=>$request->salary,
                    'previous_salary'=>$request->salary,
                    'increment_salary'=>'0',
                    'effect_date'=>$request->joiningDate,
                ]);
                return apiResponse(null,'Employee registration complete');
            });
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function update(Request $request)
    {

        try {
            $updateId = $request->id;

            $employee = DB::table('users')
                ->where('id', $updateId);

            $empImg = $employee->first();

            if ($request->hasFile('profileImage')){
                if (file_exists($empImg->profile_photo_path)){
                    unlink($empImg->profile_photo_path);
                }
                $imgPath = $this->uploadImg($request->file('profileImage'));
            }
            else{
                $imgPath = $empImg->profile_photo_path;
            }

            $updateEmployee = $employee->update([
                'name'=>$request->name,
                'father_name'=>$request->fatherName,
                'mother_name'=>$request->motherName,
                'religion'=>$request->religion,
                'gender'=>$request->gender,
                'address'=>$request->address,
                'mobile'=>$request->mobile,
                'email'=>$request->email,
                'dob'=>$request->DOB,
                'designation_id'=>$request->designation,
                'profile_photo_path'=> $imgPath,
            ]);

            if ($updateEmployee) {
                return apiResponse(null, 'Employee update complete');
            }

        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function show($employee_id)
    {
        try {
            $employee = DB::table('users as us')
                ->join('designations as des','des.id','=','us.designation_id')
                ->select('us.*','des.name as designation_name')
                ->where('us.id', $employee_id)->first();

            if ($employee != null){
                return apiResponse($employee);
            }
            return apiResponse(null,'NO data found');

        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }
}
