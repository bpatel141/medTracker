<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

class CustomThrottleRequests extends ThrottleRequests
{
    protected function buildException($request, $key, $maxAttempts, $responseCallback = null)
    {
        $response = response()->json([
            'message' => 'You have exceeded the maximum number of requests. Please wait before retrying.',
        ], Response::HTTP_TOO_MANY_REQUESTS);

        return parent::buildException($request, $key, $maxAttempts, function () use ($response) {
            return $response;
        });
    }
}
