<?php


namespace App\Http\Controllers\APIResponse;


class ApiResponse
{
    public function apiSuccess($message, $data)
    {
        $apiResponse = new \stdClass();
        $apiResponse->message = $message;
        $apiResponse->errMess = "";
        $apiResponse->data = $data;
        $apiResponse->status = true;
        return response()->json($apiResponse);
    }

    public function apiFailed($errMess, $data)
    {
        $apiResponse = new \stdClass();
        $apiResponse->message = "";
        $apiResponse->errMess = $errMess;
        $apiResponse->data = $data;
        $apiResponse->status = false;
        return response()->json($apiResponse);
    }
}
