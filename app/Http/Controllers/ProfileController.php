<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator, Auth;
class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $user = User::find(Auth::user()->id);
        
        return view('admin.profile',['user'=>$user]);
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);
        
        if($validator->fails())
        {
            return redirect()->route('admin.terms_conditions.index')->withErrors($validator)->withInput();
        } 
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $request->password != "" ? $user->password = Hash::make($request->password) : '';
        if ($files = $request->file('profile_pic')) 
        {
            $destinationPath = public_path('profile_pic/'); // upload path
            // echo $destinationPath;exit;
            $profileImage = time() . "." . $files->getClientOriginalName();
            $files->move($destinationPath, $profileImage);


            old_file_remove('profile_pic',$user->profile_pic);
            $user->profile_pic = $profileImage;    
        }
        
        $user->save();
        $message = "Profile Updated Successfully";
        return redirect()->route('admin.profile.index')->with('message', $message );
    }
}
