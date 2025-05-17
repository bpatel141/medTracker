<?php

namespace App\Traits;

trait ApiResponse
{
    public function successResponse($data = [], $message = 'Success', $query = null)
    {
        $response = [
            'success' => true,
            'code' => 200,
            'message' => $message,
            'data' => $data,
        ];

        if ($query !== null) {
            $response['query'] = $query;
        }

        return response()->json($response, 200);
    }

    public function errorResponse($data = [], $message = 'Something went wrong', $query = null)
    {
         $response = [
            'success' => false,
            'code' => 500,
            'message' => $message,
            'data' => $data,
        ];

        if ($query !== null) {
            $response['query'] = $query;
        }
        return response()->json($response, 500);
    }
}
