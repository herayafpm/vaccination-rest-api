<?php

namespace App\Http\Middleware;

use App\Models\Society;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(empty($request->query('token') ?? '')){
            return response()->json([
                'message' => 'Invalid Token'
            ],401);
        }
        $society = Society::where(['login_tokens' => $request->query('token')])->first();
        if(!$society){
            return response()->json([
                'message' => 'Invalid User'
            ],401);
        }
        return $next($request);
    }
}
