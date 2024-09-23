<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the session has the required data (e.g., user information)
        if (!$request->session()->has('user_id')) {
            return redirect('/loginpage'); // Redirect to login page if no session data
        }

        return $next($request);
    }
}
