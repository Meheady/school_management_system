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
            $allAttendance = EmployeeAttendence::with('user')->latest()->get();
            return $allAttendance;
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
}
