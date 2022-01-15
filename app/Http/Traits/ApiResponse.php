<?php


namespace App\Http\Traits;


trait ApiResponse
{
    public function apiSuccess($message, $data = [], $errMess = "", $status = true, $statusCode = 200)
    {
        $apiResponse = new \stdClass();
        $apiResponse->message = $message;
        $apiResponse->errMess = $errMess;
        $apiResponse->data = $data;
        $apiResponse->status = $status;
        return response()->json($apiResponse, $statusCode);
    }

    public function apiFailed($message = "", $data = [], $errMess = "", $status = false, $statusCode = 500)
    {
        $apiResponse = new \stdClass();
        $apiResponse->message = $message;
        $apiResponse->errMess = $errMess;
        $apiResponse->data = $data;
        $apiResponse->status = $status;
        return response()->json($apiResponse, $statusCode);
    }
}
