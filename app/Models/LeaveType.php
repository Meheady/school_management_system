<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;
    protected $guarded = [];


    public static function storeLeaveType($request)
    {
        $leaveType = new LeaveType();
        $leaveType->type_name = $request->leaveType;
        $leaveType->save();
    }
    public static function updateLeaveType($request)
    {
        $leaveType = LeaveType::find($request->id);
        $leaveType->type_name = $request->leaveType;
        $leaveType->save();
    }
}
