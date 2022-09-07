<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Throwable $e) {
            if($e instanceof TokenInvalidException){
                return response()->json(
                    ['status' => 'invalid token',
                    'error' => '400']
                );
            }

            if($e instanceof TokenExpiredException){
                return response()->json(
                    ['status' => 'Expired Token',
                    'error' => '400']
                );
            }

            return response()->json(
                ['status' => 'Unauthorized',
                'error' => '400']
            );
        }
        return $next($request);
    }
}
