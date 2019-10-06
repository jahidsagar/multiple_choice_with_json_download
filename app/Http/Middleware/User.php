<?php

namespace App\Http\Middleware;

use Closure;
use App\CheckRole as checkRole;
class User
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
        if(checkRole::hasRole(\Auth::user()->id) != 'user'){
            return redirect('dashboard');
        }
        return $next($request);
    }
}
