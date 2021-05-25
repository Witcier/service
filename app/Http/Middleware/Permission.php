<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;
use Illuminate\Support\Str;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user || $this->shouldPassThrough($request) || $this->checkRoutePermission($request)) {
            return $next($request);
        } else {
            return Response::errorUnauthorized();
        }
        return $next($request);
    }

    public function shouldPassThrough($request)
    {
        if (Authenticate::shouldPassThrough($request)) {
            return true;
        }

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

    protected function isApiRoute($request)
    {
        return $request->routeIs(api_route_name('*'));
    }

    public function checkRoutePermission(Request $request)
    {
        if (! $middleware = collect($request->route()->middleware())->first(function ($middleware) {
            return Str::startsWith($middleware, $this->middlewarePrefix);
        })) {
            return false;
        }

        $args = explode(',', str_replace($this->middlewarePrefix, '', $middleware));

        $method = array_shift($args);

        if (! method_exists(Checker::class, $method)) {
            throw new RuntimeException("Invalid permission method [$method].");
        }

        call_user_func_array([Checker::class, $method], [$args]);

        return true;
    }
}
