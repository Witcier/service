<?php

use Illuminate\Http\Request;

function api_route_name($routeName = null)
{
    return $routeName = $routeName ?  add_prefix_route($routeName) : request()->route()->getName();
}

function add_prefix_route($routeName = null)
{
    return str_replace('/', '.', request()->route()->getPrefix()) .'.'. $routeName;
}

function api_base_path($path = '')
{
    $prefix = '/'.trim(request()->route()->getPrefix(), '/');

    $prefix = ($prefix == '/') ? '' : $prefix;

    $path = trim($path, '/');

    if (is_null($path) || strlen($path) == 0) {
        return $prefix ?: '/';
    }

    return $prefix.'/'.$path;
}

function match_request_path($path, ?string $current = null)
{
    $request = request();
    $current = $current ?: $request->decodedPath();

    if (Str::contains($path, ':')) {
        [$methods, $path] = explode(':', $path);

        $methods = array_map('strtoupper', explode(',', $methods));

        if (! empty($methods) && ! in_array($request->method(), $methods)) {
            return false;
        }
    }

    // 判断路由名称
    if ($request->routeIs($path) || $request->routeIs(api_route_name($path))) {
        return true;
    }

    if (! Str::contains($path, '*')) {
        return $path === $current;
    }

    $path = str_replace(['*', '/'], ['([0-9a-z-_,])*', "\/"], $path);

    return preg_match("/$path/i", $current);
}