<?php

namespace App\Http\Middleware;

use App\Business;
use App\helpers\HttpStatuses;
use Closure;

class CheckApiCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$apiKey = $request->header('x-api-key')) {
            return response()->json([
                'error' => 'No api key found. You need an api key in order to access this api! Please contact an admin'
            ],  HttpStatuses::BAD_REQUEST_400);
        }

        /**@var Business $business */
        if (!$business = Business::query()->where('api_key', $apiKey)->first()) {
            return response()->json([
                'error' => 'Invalid api key. Unable to process your request!'
            ],  HttpStatuses::BAD_REQUEST_400);
        }

        if (strcasecmp($request->header('authorization'), self::generateAuthorizationString($business)) !== 0) {
            return response()->json([
                'error' => 'Invalid credentials sent. Please validate your user credentials and try again!'
            ],  HttpStatuses::BAD_REQUEST_400);
        }

        return $next($request);
    }

    /**
     * @param Business $business
     * @return string
     */
    protected function generateAuthorizationString(Business $business)
    {
        return 'Basic ' .base64_encode($business->username . ':' . $business->password);
    }
}
