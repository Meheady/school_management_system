<?php

namespace App\Http\Controllers\backend\employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendence;
use Illuminate\Http\Request;

class MonthlySalaryController extends Controller
{
    public function index(Request $request)
    {
        $YearMonth =  date('Y-m', strtotime($request->date));

        $thisMonthEmp = EmployeeAttendence::with('user')
            ->where('date','like',$YearMonth.'%')
            ->select('employee_id','date')
            ->groupBy('employee_id')->get();

        $data = [];

        foreach ($thisMonthEmp as $index=>$item){

            $totalAbsent = EmployeeAttendence::where('employee_id',$item->employee_id)->where('status','absent')->get();
            $countAbsent = count($totalAbsent);
            $dailySalary = $item->user->salary / 30;
            $countRoundAbsent = floor($countAbsent / 3);
            $deductSalary = $dailySalary * $countRoundAbsent;
            $finalSalary = $item->user->salary - $deductSalary;

            $singleData = [
                'employee_id'=> $item->employee_id,
                'name'=>$item->user->name,
                'salary'=>$finalSalary,
                'month'=>date('MM-Y',strtotime($item->date))
            ];
            $data[]= $singleData;

        }

        return $data;


        return $thisMonthEmp;
    }
}
