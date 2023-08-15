<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class FeeCategoryController extends Controller
{
    public function index()
    {
        try {
            $group = DB::table('fee_categories')->select('id','name')->get();
            if ($group != null){
                return apiResponse($group);
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
            $group = DB::table('fee_categories')->where('id', $id)->select('id','name')->first();
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
            'name'=> 'required|unique:fee_categories,name'
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $insertGroup = DB::table('fee_categories')->insert([
                'name'=> $request->name,
            ]);
            if ($insertGroup > 0){
                return apiResponse(null,'Fee Category insert successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required|unique:fee_categories,name,'.$request->id
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $updateGroup = DB::table('fee_categories')->where('id', $request->id)->update([
                'name'=> $request->name,
            ]);
            if ($updateGroup > 0){
                return apiResponse(null,'Fee Category update successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function destroy($id)
    {

        try {
            $deleteGroup = DB::table('fee_categories')->where('id', $id)->delete();
            if ($deleteGroup > 0){
                return apiResponse(null,'Fee Category delete successfully');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }
}
