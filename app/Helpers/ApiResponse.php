<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, $message = 'Success', $status = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        return response()->json($response, $status);
    }

    public static function error($message = 'Error', $errors = null, $status = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}
