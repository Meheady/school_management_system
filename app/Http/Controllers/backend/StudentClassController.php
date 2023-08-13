<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class StudentClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = DB::table('student_classes')->select('id','name')->get();
        return $classes;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'className'=>'required|unique:student_classes,name'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error'=>$validator->errors()
            ]);
        }

        try {
            $insertClass = DB::table('student_classes')->insert([
                'name'=>$request->className,
            ]);
            return apiResponse(null,'Class save successfully');
        }
        catch (Exception $e){
            return apiError($e->getMessage(),$e->getCode());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        try {
            $class = DB::table('student_classes')->where('id',$request->id)->first();
            if ($class != null){
                return apiResponse($class,'');
            }
            else{
                return apiResponse(null,'No data found');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage(),$e->getCode());
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'className'=> 'required|unique:student_classes,name,'.$request->id
        ]);
        if ($validator->fails()){
            return response()->json([
                'error'=>$validator->errors()
            ]);
        }

        try {
            $updateClass = DB::table('student_classes')->where('id',$request->id)
                ->update([
                    'name'=>$request->className
                ]);
            return apiResponse(null,'Class update successfully');
        }
        catch (Exception $e){
            return apiError($e->getMessage(),$e->getCode());
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        try {
            $class = DB::table('student_classes')->where('id',$request->id)->delete();
            return apiResponse(null,'Class Delete successfully');
        }
        catch (Exception $e){
            return apiError($e->getMessage(),$e->getCode());
        }
    }
}
