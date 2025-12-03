<?php
namespace App\Helpers;

class ApiResponseHelper
{
    public static function success($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function error($message = 'Error', $code = 400, $errors = null)
    {
        $response = [
            'status' => $code,
            'message' => $message
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    public static function notFound($message = 'Resource not found')
    {
        return self::error($message, 404);
    }
}
