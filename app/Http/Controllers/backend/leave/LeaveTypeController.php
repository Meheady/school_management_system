<?php

namespace App\Http\Controllers\backend\leave;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeaveTypeController extends Controller
{
    public function index()
    {
        try {
            $allLeaveType = LeaveType::latest()->get();
            if ($allLeaveType != null){
                return apiResponse($allLeaveType);
            }
            return apiResponse(null,'No data found');
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $leaveType = LeaveType::findOrFail($id);
            if ($leaveType != null){
                return apiResponse($leaveType);
            }
            return apiResponse(null,'No data found');
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'leaveType'=> 'required|unique:leave_types,type_name'
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {

            LeaveType::storeLeaveType($request);
            return apiResponse(null,'Leave type insert successfully');
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'leaveType'=> 'required|unique:leave_types,type_name,'.$request->id
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {

            LeaveType::updateLeaveType($request);
            return apiResponse(null,'Leave type update successfully');
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function destroy($id)
    {

        try {
            $deleteLeaveType = LeaveType::findOrFail($id)->delete();
            if ($deleteLeaveType > 0){
                return apiResponse(null,'Leave type delete successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }
}
