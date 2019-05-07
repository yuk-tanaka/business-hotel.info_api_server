<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;

/**
 * http://randomproblems.com/api-rate-limiting-lumen-5-6-illuminate-routing-throttlerequests-class/
 * Class RateLimits
 */
class RateLimits extends ThrottleRequests
{
    /**
     * @param Request $request
     * @return string
     */
    protected function resolveRequestSignature($request)
    {
        return sha1(implode('|', [
                $request->method(),
                $request->root(),
                $request->path(),
                $request->ip(),
                $request->query('access_token')
            ]
        ));

        return $request->fingerprint();
    }
}