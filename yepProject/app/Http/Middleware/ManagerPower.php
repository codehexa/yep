<?php

namespace App\Http\Middleware;

use App\Models\Configurations;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerPower
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
        if (Auth::check()){
            if (Auth::user()->power == Configurations::$USER_POWER_MANAGER || Auth::user()->power == Configurations::$USER_POWER_ADMIN){
                return $next($request);
            }
        }

        return redirect("/");
    }
}
