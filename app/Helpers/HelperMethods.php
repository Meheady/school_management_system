<?php

function apiResponse($data=null, $message='',$status=200){
    $response = [
        'status' => $status >= 200 && $status < 300,
        'message' => $message,
        'data' => $data,
    ];

    return response()->json($response,$status);
}

function apiError ($message = 'An error occurred', $status = 500){
    return apiResponse(null, $message,$status);
}

