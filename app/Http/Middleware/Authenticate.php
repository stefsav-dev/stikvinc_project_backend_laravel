<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    protected function redirectTo($request) {
        if (!$request->expectsJson()) {
            return response()->json(
                [
                    "message" => "Unauthorized",
                    "status" => Response::HTTP_UNAUTHORIZED],
            );
        }
    }
}
