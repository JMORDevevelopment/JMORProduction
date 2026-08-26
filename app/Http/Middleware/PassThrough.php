<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PassThrough
{
    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }
}
