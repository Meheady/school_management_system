<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    public function index()
    {
        try {
            $designation = DB::table('designations')->select('id','name')->get();
            if ($designation != null){
                return apiResponse($designation);
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
            $group = DB::table('designations')->where('id', $id)->select('id','name')->first();
            if ($group != null){
                return apiResponse($group);
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
            'name'=> 'required|unique:designations,name'
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $insertDesignation = DB::table('designations')->insert([
                'name'=> $request->name,
            ]);
            if ($insertDesignation > 0){
                return apiResponse(null,'Designation insert successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required|unique:designations,name,'.$request->id
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $updateDesignation = DB::table('designations')->where('id', $request->id)->update([
                'name'=> $request->name,
            ]);
            if ($updateDesignation > 0){
                return apiResponse(null,'Designation update successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function destroy($id)
    {

        try {
            $deleteDesignation = DB::table('designations')->where('id', $id)->delete();
            if ($deleteDesignation > 0){
                return apiResponse(null,'Designation delete successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }
}
