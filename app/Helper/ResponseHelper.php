<?php

namespace App\Helper;

class ResponseHelper
{
    public function __construct()
    {
    }

    /**
     * Prepare Success response
     * @param string $status
     * @param string message
     * @param array data
     * @param integer statusCode
     * @return response
     */
    public static function success($status = 'success', $message = null, $data = [], $statusCode = 200) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Prepare Success response
     * @param string $status
     * @param string message
     * @param integer statusCode
     * @return response
     */
     public static function error($status = 'error', $message = null, $statusCode = 500) {
        return response()->json([
            'status' => $status,
            'message' => $message
        ], $statusCode);
     }
}
