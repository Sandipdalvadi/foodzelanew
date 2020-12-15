<?php

namespace App\Http\Middleware;
use App\Models\Permissions;
use URL;
use Closure;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if(auth()->user()){
            if(auth()->user()->role == 1){
                return $next($request);
            }
            if(auth()->user()->role == 5){
                $manageView = [];
                if(auth()->user()->permissions != ''){
                    $userPermission = explode(",",auth()->user()->permissions);
                    $manageView = Permissions::whereIn('id', $userPermission)->get();
                }
                else{
                    abort(403, 'Unauthorized action.');
                    return false;
                }
                foreach($manageView as $view){
                    if (\Request::is($view->url))
                    {
                        return $next($request); 
                    }
                }
                if (\Request::is('admin/home'))
                {
                    $currentUrl = URL::to('/'); 
                    return redirect($currentUrl.$view->full_url);     
                }
                abort(403, 'Unauthorized action.');
                return false;
            }
            return redirect('login')->with('error',"You don't have any access.");
        }
        else{
            return redirect('login')->with('error',"You don't have any access.");
        }
   
    }
}