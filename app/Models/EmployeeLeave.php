<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function employee()
    {
        return $this->hasOne(User::class,'employee_id','id');
    }
    public function leaveType()
    {
        return $this->hasOne(LeaveType::class,'leave_type_id','id');
    }
}
