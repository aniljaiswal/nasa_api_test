<?php

namespace App\Http\Middleware;

use Closure;

class VerifyAcceptHeader
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
        if (!$request->wantsJson()) {
            return response()->json([
                'error' => 'Accept header is missing or invalid.',
            ], 422);
        }
        
        return $next($request);
    }
}
