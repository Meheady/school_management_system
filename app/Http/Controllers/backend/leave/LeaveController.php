<?php

namespace App\Http\Controllers\backend\leave;

use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        try {

            return EmployeeLeave::with('employee','leaveType')->latest()->get();

        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            EmployeeLeave::storeLeave($request);
            return apiResponse(null,'Leave applied');
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }
    public function update(Request $request)
    {
        try {
            EmployeeLeave::updateLeave($request);
            return apiResponse(null,'Leave applied updated');
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }

}
