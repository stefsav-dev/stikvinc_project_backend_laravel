<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (!$request->user()) {
            return response()->json(["message" => "Unauthenticated"], 401);
        }

        return $next($request);
    }
}
