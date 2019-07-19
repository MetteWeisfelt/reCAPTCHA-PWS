<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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
        if(auth()->user()->isAdmin()) {
            return $next($request);
        }

        Auth::logout();
        return redirect('/login')->with('error', 'Dit account heeft geen beheerdersrechten, u bent uitgelogd.');
    }
}