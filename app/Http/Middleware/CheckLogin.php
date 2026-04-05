<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
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
        if (\Auth::user()->name == 'ppic' || \Auth::user()->name == 'finance' || \Auth::user()->name == 'pc') {
            return redirect('/dashboard');
        }
        return $next($request);
    }
}
