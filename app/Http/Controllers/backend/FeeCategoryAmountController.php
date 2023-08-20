<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class FeeCategoryAmountController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'fee_category'=> 'required',
            'class'=> 'required',
            'amount'=> 'required'
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $countClass = count($request->class);
            $countAmount = count($request->amount);

            if ($countClass == $countAmount){
                $records = [];
                foreach ($request->class as $index => $item){
                    $record = [
                        'fee_category'=> $request->fee_category,
                        'class'=> $request->class[$index],
                        'amount'=> $request->amount[$index],
                    ];
                    $records[] = $record;
                }
                $insertFeeCatAmt = DB::table('fee_category_amounts')->insert($records);
                if ($insertFeeCatAmt > 0){
                    return apiResponse(null,'Fee Category Amount insert successfully');
                }

            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }


    public function index()
    {
        try {
            $feeCatAmt = DB::table('fee_category_amounts')
                ->join('fee_categories','fee_categories.id','=','fee_category_amounts.fee_category')
                ->select('fee_category_amounts.*','fee_categories.name as category_name')
                ->groupBy('fee_category_amounts.fee_category')
                ->get();

            if ($feeCatAmt != null){
                return apiResponse($feeCatAmt);
            }
            return apiResponse(null,'No data found');
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function show ($category_id)
    {
        try {
            $feeCatAmt = DB::table('fee_category_amounts')
                ->join('student_classes','student_classes.id','=','fee_category_amounts.class')
                ->join('fee_categories','fee_categories.id','=','fee_category_amounts.fee_category')
                ->select('fee_category_amounts.*','student_classes.name as className','fee_categories.name as feeCatName')
                ->where('fee_category', $category_id)->get();
            if ($feeCatAmt != null){
                return apiResponse($feeCatAmt);
            }
            return apiResponse(null,'No data found');
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'fee_category'=> 'required',
            'class'=> 'required',
            'amount'=> 'required'
        ]);
        if ($validator->fails()){
            return apiError($validator->errors());
        }

        try {
            $countClass = count($request->class);
            $countAmount = count($request->amount);

            if ($countClass == $countAmount){
                DB::table('fee_category_amounts')
                    ->where('fee_category',$request->feeCatId)->delete();
                $records = [];

                foreach ($request->class as $index => $item){
                    $record = [
                        'fee_category'=> $request->fee_category,
                        'class'=> $request->class[$index],
                        'amount'=> $request->amount[$index],
                    ];
                    $records[] = $record;
                }
                $insertFeeCatAmt = DB::table('fee_category_amounts')
                    ->insert($records);
                if ($insertFeeCatAmt > 0){
                    return apiResponse(null,'Fee Category Amount update successfully');
                }

            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

}
