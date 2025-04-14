<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $guards = ['cook', 'waiter', 'web'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
