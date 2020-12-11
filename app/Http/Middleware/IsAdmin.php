<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {
    //     return $next($request);
    // }
    public function handle($request, Closure $next)
    {
        if(auth()->user()){
            if(auth()->user()->role == 1){
                return $next($request);
            }
            return redirect('login')->with('error',"You don't have any access.");
        }
        else{
            return redirect('login')->with('error',"You don't have any access.");
        }
   
    }
}