<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function forgotPwd(Request $request)
    {
        if ($request->id == 0|| $request->token == '') {
            $message = "Your forgot password link has been expired";
            return redirect('/')->with('errorMessage', $message);
        }
    
        $e = User::where('id', $request->id)->where('passwordResetCode', $request->token)->first();
    
        $id=$request->id;
        if (empty($e)) {
            $message = "Your forgot password link has been expired";
            return redirect('/')->with('errorMessage', $message);
        }
        return view('forgotpwd', compact('id'));
    }

    public function passwordChange1(Request $request)
    {
        if ($request->id) {
            $user = User::find($request->id);
            $user->password = Hash::make($request->password);
            $user->save();
            $message = "Password Change Successfully";
            return redirect('/password_success')->with('message', $message);
        }
    }

    public function password_success(Request $request){
        return view('password_success');
    }
    public function adminHome()
    {
        return view('admin.dashboard');
    }
}
