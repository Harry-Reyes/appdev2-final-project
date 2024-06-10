<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasAdminToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->input('token') === null)
        {
            return response()->json([
                'message'=> 'Token is missing'
            ], 401);
        }
        else if ($request->input('token') !== env('ADMIN_TOKEN'))
        {
            return response()->json([
                'message'=> 'Token is invalid'
            ], 401);
        }
        return $next($request);
    }
}
