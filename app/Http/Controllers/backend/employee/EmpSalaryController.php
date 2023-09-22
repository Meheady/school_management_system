<?php

namespace App\Http\Controllers\backend\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpSalaryController extends Controller
{
    public function index()
    {
        try {

            $employee = DB::table('users')
                ->where('usertype','employee')
                ->select('id','name','salary','join_date','gender','mobile')
                ->orderBy('id','asc')->get();

            if ($employee != null){
                return apiResponse($employee);
            }
            else{
                return  apiResponse(null,'No data found');
            }
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }
    public function show($employee_id)
    {
        try {

            $employee = DB::table('users')
                ->select('id','salary')
                ->where('id',$employee_id)
                ->first();

            if ($employee != null){
                return apiResponse($employee);
            }
            else{
                return  apiResponse(null,'No data found');
            }
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }
    public function salaryDetails($employee_id)
    {
        try {
            $details['employee'] = DB::table('users')->where('id',$employee_id)->select('name','join_date')->first();
            $details['salaryDetails'] = DB::table('employee_salary_logs')
                ->select('*')
                ->where('employee_id',$employee_id)
                ->get();

            if ($details != null){
                return apiResponse($details);
            }
            else{
                return  apiResponse(null,'No data found');
            }
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }


    public function storeSalary(Request $request)
    {
        try {
            $employee = DB::table('users')->findOr($request->id);
            return DB::transaction(function () use ($employee, $request){
                $pevSalary = $employee->salary;
                $presSalary = (float)$request->incrementSalary + (float)$pevSalary;

                DB::table('users')->where('id',$employee->id)->update([
                    'salary'=>$presSalary
                ]);

                $employeeSalary = DB::table('employee_salary_logs')->insert([
                    'employee_id'=>$employee->id,
                    'present_salary'=>$presSalary,
                    'previous_salary'=>$employee->salary,
                    'increment_salary'=>$request->incrementSalary,
                    'effect_date'=>$request->effectDate,
                ]);

                return apiResponse(null, 'Salary increment successfully');
            });
        }
        catch(\Exception $e){
            return apiError($e->getMessage());
        }
    }
}
