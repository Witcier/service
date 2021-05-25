<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public static function shouldPassThrough($request)
    {
        $excepts = config('api.permission.except');
        foreach ($excepts as $except) {
            if ($request->routeIs($except) || $request->routeIs(api_route_name($except))) {
                return true;
            }

            $except = api_base_path($except);

            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if (match_request_path($except)) {
                return true;
            }
        }

        return false;
    }
}
