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
}
