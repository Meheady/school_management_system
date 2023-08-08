<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        $insertClass = DB::table('student_classes')->insert([
           'name'=>$request->className,
        ]);

        if ($insertClass > 0){
            return response()->json([
                'message'=>'Class save successfully'
            ]);
        }
        return response()->json([
        'error'=>'Try again, something went wrong'
    ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $class = DB::table('student_classes')->where('id',$request->id)->first();
        if ($class != null){
            return response()->json([
                'data'=>$class
            ]);
        }
        else{
            return response()->json([
               'data'=>null
            ]);
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

        $updateClass = DB::table('student_classes')->where('id',$request->id)
            ->update([
               'name'=>$request->className
            ]);

        if ($updateClass > 0){
            return response()->json([
                'message'=>'Class update successfully'
            ]);
        }
        return response()->json([
            'error'=>'Try again, something went wrong'
        ]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $class = DB::table('student_classes')->where('id',$request->id)->delete();
        if ($class > 0){
            return response()->json([
                'message'=> "Delete successfully"
            ]);
        }
        else {
            return response()->json([
                'error'=>'Try again, something went wrong'
            ]);
        }
    }
}
