<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class AssignSubjectController extends Controller
{

    public function index()
    {
        try {
            $assignSub = DB::table('assign_subjects')
                ->join('student_classes','student_classes.id','=','assign_subjects.class_id')
                ->select('assign_subjects.*','student_classes.name as className')
                ->groupBy('assign_subjects.class_id')
                ->get();

            if ($assignSub != null){
                return apiResponse($assignSub);
            }
            return apiResponse(null,'No data found');
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function show($classId)
    {
        try {
            $assignSub = DB::table('assign_subjects')
                ->where('class_id',$classId)
                ->join('student_classes','student_classes.id','=','assign_subjects.class_id')
                ->join('school_subjects','school_subjects.id','=','assign_subjects.subject_id')
                ->select('assign_subjects.*','student_classes.name as className','school_subjects.subject_name as subjectName')
                ->get();

            if ($assignSub != null){
                return apiResponse($assignSub);
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
            "classId"=> 'required',
            "subjectId"=> 'required|array',
            "fullMark"=> 'required|array',
            "passMark"=> 'required|array',
            "subjectiveMark"=> 'required|array',
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try{

            $countSubject = count($request->subjectId);

            $records= [];

            foreach ($request->subjectId as $index=> $item){
                $record = [
                  'class_id'=>$request->classId,
                  'subject_id'=>$request->subjectId[$index],
                  'full_mark'=>$request->fullMark[$index],
                  'pass_mark'=>$request->passMark[$index],
                  'subjective_mark'=>$request->subjectiveMark[$index],
                ];
                $records[] = $record;
            }

            $insertAsngSub = DB::table('assign_subjects')
                ->insert($records);
            if ($insertAsngSub > 0){
               return apiResponse(null,'Subject assign successfully');
            }
            else{
                return apiError();
            }

        }
        catch(Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "classId"=> 'required',
            "subjectId"=> 'required|array',
            "fullMark"=> 'required|array',
            "passMark"=> 'required|array',
            "subjectiveMark"=> 'required|array',
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try{
            DB::table('assign_subjects')
                ->where('class_id',$request->classId)->delete();

            $countSubject = count($request->subjectId);

            if ($countSubject > 0){
                $records= [];

                foreach ($request->subjectId as $index=> $item){
                    $record = [
                        'class_id'=>$request->classId,
                        'subject_id'=>$request->subjectId[$index],
                        'full_mark'=>$request->fullMark[$index],
                        'pass_mark'=>$request->passMark[$index],
                        'subjective_mark'=>$request->subjectiveMark[$index],
                    ];
                    $records[] = $record;
                }

                $insertAsngSub = DB::table('assign_subjects')
                    ->insert($records);
                if ($insertAsngSub > 0){
                    return apiResponse(null,'Subject assign update successfully');
                }
                else{
                    return apiError();
                }
            }
            else{
                return apiError();
            }



        }
        catch(Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function destroy($id)
    {

    }
}
