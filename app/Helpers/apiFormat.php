<?php

use Illuminate\Http\Response;

function apiReturn($data, $message = null)
{
    return [
        'success' => true,
        'status_code' => 200,
        'status' => 'OK',
        'message' => $message,
        'data' => $data
    ];
}
function apiFailed($data = null, $message = null)
{
    $failed_format = [
        'success' => false,
        'status_code' => 400,
        'status' => 'Failed',
        'message' => $message,
        'data' => $data
    ];
    return response()->json($failed_format, 400);
}

function apiCreated($data = null, $message = null)
{
    $created_format =  [
        'success' => true,
        'status_code' => 201,
        'status' => 'Created',
        'message' => $message,
        'data' => $data
    ];
    return response()->json($created_format, 201);
}

function apiUnauthorized($message = "asd")
{
    $unauthorized_format =  [
        'success' => false,
        'status_code' => 401,
        'status' => 'Unauthorized',
        'message' => $message
    ];
    return response()->json($unauthorized_format, 401);
}

function apiCatch()
{
    $catch_format = [
        'success' => false,
        'status_code' => 500,
        'status' => 'server error',
        'message' => "maaf terjadi kesalahan sistem"
    ];
    return response()->json($catch_format, 500);
}

function apiValidate($error)
{
    $error_validate_format =  [
        'success' => false,
        'status_code' => 422,
        'message' => 'The given data was invalid',
        'errors' => $error
    ];
    return response()->json($error_validate_format, 422);
}