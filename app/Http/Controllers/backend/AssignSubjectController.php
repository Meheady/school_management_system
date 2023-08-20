<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class AssignSubjectController extends Controller
{

    public function index()
    {
        try {
            $allData = DB::table('assign_subjects')->select('*')->get();
            if ($allData != null){
                return apiResponse($allData);
            }
            else{
                apiResponse(null,'No data found');
            }
        }
        catch (Exception $e){
            return apiError($e->getMessage());
        }
    }

    public function show($id)
    {

    }

    public function store(Request $request)
    {

    }

    public function update(Request $request)
    {

    }

    public function destroy($id)
    {

    }
}
