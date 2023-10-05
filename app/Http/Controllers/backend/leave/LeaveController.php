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
    public function store()
    {
        try {

        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }

}
