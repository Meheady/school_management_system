<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    use HasFactory;
    protected $guarded = [];


    public static function storeLeave($request)
    {
        EmployeeLeave::create([
           'employee_id' => $request->employeeId,
           'leave_type_id' => $request->leaveTypeId,
           'start_date' => date('Y-m-d', strtotime($request->startDate)),
           'end_date' => date('Y-m-d', strtotime($request->endDate)),
           'leave_purpose' => $request->leavePurpose,
        ]);
    }
    public static function updateLeave($request)
    {
        EmployeeLeave::find($request->id)->update([
           'employee_id' => $request->employeeId,
           'leave_type_id' => $request->leaveTypeId,
           'start_date' => date('Y-m-d', strtotime($request->startDate)),
           'end_date' => date('Y-m-d', strtotime($request->endDate)),
           'leave_purpose' => $request->leavePurpose,
        ]);
    }

    public function employee()
    {
        return $this->hasOne(User::class,'id','employee_id');
    }
    public function leaveType()
    {
        return $this->hasOne(LeaveType::class,'id','leave_type_id');
    }
}
