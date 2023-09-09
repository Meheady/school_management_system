<?php

namespace App\Http\Controllers\backend\student;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class RegistrationFeeController extends Controller
{
    public function search(Request $request)
    {
        $year = $request->year;
        $class = $request->class;

        try {
            $searchStudent = DB::table('student_registrations AS sr')
                ->join('users AS u','u.id','=','sr.student_id')
                ->join('student_years AS yr','yr.id','=','sr.year_id')
                ->join('student_classes AS cls','cls.id','=','sr.class_id')
                ->join('discount_students AS ds','ds.student_reg_id','=','sr.id')
                ->select('sr.id','sr.student_id','u.name as student_name','u.id_no','yr.name as student_year','cls.id as class_id','cls.name as student_class','ds.fee_category_id','ds.discount')
                ->where('sr.class_id',$class)->where('sr.year_id',$year);
            if ($searchStudent != null){

                $allStudent = $searchStudent->get();
                $student = $searchStudent->first();

                $feeAmount = DB::table('fee_category_amounts')
                    ->where('fee_category', $student->fee_category_id)
                    ->where('class',$student->class_id)
                    ->first();
                $data = [];
                foreach($allStudent as $index=>$item){
                    $discountAmount = $feeAmount->amount * $item->discount / 100;
                    $finalAmount = $feeAmount->amount - $discountAmount;
                    $setData = [
                      'id'=>$item->id,
                      'student_id'=>$item->student_id,
                      'id_no'=>$item->id_no,
                      'student_name'=>$item->student_name,
                      'student_year'=>$item->student_year,
                      'class_id'=>$item->class_id,
                      'student_class'=>$item->student_class,
                      'discount'=>$item->discount.'%',
                      'discount_amount'=>$finalAmount,
                    ];

                    array_push($data,$setData);
                }
                return apiResponse($data);
            }
            return apiResponse(null,'No data found');
        }
        catch (\Exception $e){
            return apiError($e->getMessage());
        }
    }


    public function  generate(Request $request){
        $student = $request->student_id;
        $class = $request->class_id;


        try {
            $data = DB::table('student_registrations AS sr')
                ->join('users AS u','u.id','=','sr.student_id')
                ->join('discount_students AS ds','ds.student_reg_id','=','sr.id')
                ->join('student_years AS yr','yr.id','=','sr.year_id')
                ->join('student_classes AS cls','cls.id','=','sr.class_id')
                ->select('sr.id','sr.student_id','sr.roll','u.name as student_name','u.father_name','u.mother_name','u.id_no','ds.fee_category_id','ds.discount','yr.name as year','cls.name as class')
                ->where('sr.student_id',$student)->where('sr.class_id',$class)->first();

            if ($data != null){

                $feeAmount = DB::table('fee_category_amounts')
                    ->where('fee_category', $data->fee_category_id)
                    ->where('class',$class)
                    ->first();

                $discountAmount = $feeAmount->amount * $data->discount / 100;
                $finalAmount = $feeAmount->amount - $discountAmount;

                $pdf = Pdf::loadView('pdf.registration_fee_slip',compact('data','finalAmount','feeAmount'));

                $base64Pdf = base64_encode($pdf->output());

                return apiResponse($base64Pdf);

            }
            else{
                return apiResponse(null,'Student not found');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }

    }
}
