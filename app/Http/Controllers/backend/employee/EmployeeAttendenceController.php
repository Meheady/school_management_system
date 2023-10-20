<?php

namespace App\Http\Controllers\backend\employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendence;
use Illuminate\Http\Request;

class EmployeeAttendenceController extends Controller
{
    public function index()
    {
        try {
            $allAttendance = EmployeeAttendence::groupBy('date')->select('id','date')->latest()->get();
            return apiResponse($allAttendance);
        }
        catch (\Exception $e){
            return  apiError($e->getMessage());
        }
    }
    public function show($date)
    {
        try {
            $getdate = date('Y-m-d',strtotime($date));
            $allAttendance = EmployeeAttendence::with('user')->where('date', $getdate)->get();
            return apiResponse($allAttendance);
        }
        catch (\Exception $e){
            return  apiError($e->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {

            $attendanceDate = $request->attendanceDate;
            $countData = count($request->employeeStatus);
            $employeeStatus = $request->employeeStatus;

            if ($countData > 0){
                $attendanceData = [];
                foreach ($employeeStatus as $item){
                    $record = [
                        'employee_id'=>$item['employeeId'],
                        'status'=>$item['status'],
                        'date'=>$attendanceDate
                    ];

                    $attendanceData[] = $record;
                }
                EmployeeAttendence::storeAttendance($attendanceData);

                return apiResponse('Attendance insert successfully');
            }

        } catch (\Exception $e) {
            return  apiError($e->getMessage());
        }
    }
    public function update(Request $request)
    {
        try {
            $attendanceDate = date('Y-m-d', strtotime($request->attendanceDate));
            EmployeeAttendence::where('date', $attendanceDate)->delete();
            $countData = count($request->employeeStatus);
            $employeeStatus = $request->employeeStatus;

            if ($countData > 0){
                $attendanceData = [];
                foreach ($employeeStatus as $item){
                    $record = [
                        'employee_id'=>$item['employeeId'],
                        'status'=>$item['status'],
                        'date'=>$attendanceDate
                    ];

                    $attendanceData[] = $record;
                }
                EmployeeAttendence::storeAttendance($attendanceData);

                return apiResponse('Attendance update successfully');
            }

        } catch (\Exception $e) {
            return  apiError($e->getMessage());
        }
    }
}
