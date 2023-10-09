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
            $allAttendance = EmployeeAttendence::latest()->get();
            return $allAttendance;
        }
        catch (\Exception $e){

        }
    }
}
