<?php

namespace App\Http\Middleware;
use App\User;
use URL;
use Closure;

class APIRow
{
    public function handle($request, Closure $next)
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);  

        // echo "<pre>";
        // print_r($request->all());
        // exit;
        if((!isset($post['loginToken'])) || empty($post['loginToken'])){
            return response()->json([
                'status' => 0,
                'error' => "Unauthorized access"
            ], 401);
        }else{
            $access_token = User::where("login_token", $post['loginToken'])->first();
            if($access_token==null){
                return response()->json([
                    'status' => 0,
                    'error' => "Unauthorized access"
                ], 401);
            }
        }
        return $next($request);
    }
}