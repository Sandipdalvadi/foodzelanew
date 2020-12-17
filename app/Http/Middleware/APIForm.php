<?php

namespace App\Http\Middleware;
use App\User;
use URL;
use Closure;

class APIForm
{
    public function handle($request, Closure $next)
    {
        $post = $request->all();
        $decode = isset($post['json_content']) ? json_decode($post['json_content']) : [];
        
        // echo "<pre>";
        // print_r($request->all());
        // exit;
        if((!isset($decode->loginToken)) || empty($decode->loginToken)){
            return response()->json([
                'error' => "Unauthorized access"
            ], 401);
        }else{
            $access_token = User::where("login_token", $decode->loginToken)->first();
            if($access_token==null){
                return response()->json([
                    'status' => false,
                    'error' => "Unauthorized access"
                ], 401);
            }
        }
        return $next($request);
    }
}