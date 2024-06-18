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
        if (($request->input('token') === null) or ($request->input('token') !== env('ADMIN_TOKEN')))
        {
            return response()->json([
                'message'=> 'Access denied.'
            ], 403);
        }
        return $next($request);
    }
}
