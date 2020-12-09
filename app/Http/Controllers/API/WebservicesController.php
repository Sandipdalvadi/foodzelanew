<?php 

namespace App\Http\Controllers\API;

use Validator, DB, Hash, Auth, Carbon, Session, Lang, App, URL;
use App\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

class WebservicesController extends Controller
{
    public function register(Request $request)
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        try {
            if((!isset($post['name'])) || (!isset($post['email'])) || (!isset($post['role'])) || (!isset($post['phone'])) || (!isset($post['isSocial'])) || (!isset($post['isEmailVerified']))
            || (!isset($post['isPhoneVerified'])) || (!isset($post['deviceToken'])) || (!isset($post['deviceType'])) || (!isset($post['status'])) || 
            ($post['name'] =="") || ($post['email'] =="") || ($post['role'] =="") ||  ($post['isSocial'] =="") || ($post['isEmailVerified'] =="") || 
            ($post['isPhoneVerified'] =="") || ($post['deviceToken'] =="") || ($post['status'] ==""))
            {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            if(($post['isSocial'] == 0) && ((!isset($post['password']) || ($post['password'] =="") || ($post['phone'] =="")))){
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            } 
            elseif(($post['isSocial'] == 1) && ((!isset($post['socialType'])) || (!isset($post['socialId'])) || ($post['socialType'] =="") || ($post['socialId'] =="")))
            {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $checkEmail = User::where('email',$post['email'])->first();
            if (!empty($checkEmail) && $post['isSocial'] == 0) {
                $response = array('success' => 0, 'message' => 'This Email Already Exists');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            elseif(!empty($checkEmail) && $post['isSocial'] == 1){

                $loginToken=substr(str_shuffle($checkEmail->id.'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 20);
                $checkEmail->login_token = $loginToken;
                $checkEmail->save();
                $userData=[];
                $userData = $this->userDetailResponse($checkEmail);
                $response = array('success' => 1, 'message' => 'User Login Succeessfully','result' => $userData);
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $newUser = new User;
            $newUser->name = $post['name']; 
            $newUser->email = $post['email']; 
            $newUser->password = $post['isSocial'] == 1 ? '' : Hash::make($post['password']); 
            $newUser->role = $post['role']; 
            $newUser->phone = $post['phone']; 
            $newUser->is_social = $post['isSocial']; 
            $newUser->profile_link = $post['isSocial'] == 1 ? 1 : 0;
            $newUser->profile_pic = $newUser->profile_link == 1 ? $post['profileLink'] : '';
            $newUser->social_type = $post['isSocial'] == 1 ? $post['socialType'] : ''; 
            $newUser->social_id = $post['isSocial'] == 1 ? $post['socialId'] : ''; 
            $newUser->is_email_verified = $post['isEmailVerified']; 
            $newUser->is_phone_verified = $post['isPhoneVerified']; 
            $newUser->device_token = $post['deviceToken']; 
            $newUser->device_type = $post['deviceType']; 
            $newUser->status = $post['status']; 
            // $newUser->status = 1; 
            $newUser->save();
            $loginToken=substr(str_shuffle($newUser->id.'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 20);
            $newUser->login_token = $loginToken;
            $newUser->save();
            $userData = $this->userDetailResponse($newUser);
            $response = array('success' => 1, 'message' => 'User Registered Succeessfully','result' => $userData);
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        } catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }
    public function userDetailResponse($user = []){
        $userData=[];
        $userData['id']=$user->id;
        $userData['name'] = $user->name ? $user->name : '';
        $userData['email'] = $user->email ? $user->email : '';
        $userData['role'] = $user->role ? $user->role : '';
        $userData['phone'] = $user->phone ? $user->phone : '';
        $userData['profilePic'] = $user->profile_pic ? $user->profile_pic : '';
        $userData['isSocial'] = $user->is_social ? $user->is_social : 0;
        $userData['socialType'] = $user->social_type ? $user->social_type : '';
        $userData['socialId'] = $user->social_id ? $user->social_id : '';
        $userData['isEmailVerified'] = $user->is_email_verified ? $user->is_email_verified : 0;
        $userData['isPhoneVerified'] = $user->is_phone_verified ? $user->is_phone_verified : 0;
        $userData['deviceToken'] = $user->device_token ? $user->device_token : '';
        $userData['deviceType'] = $user->device_type ? $user->device_type : 1;
        $userData['loginToken'] = $user->login_token ? $user->login_token : '';
        return $userData;
    } 
  
    public function login(Request $request)
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        
        try {
            if ((!isset($post['isSocial'])) || (!isset($post['email'])) || (!isset($post['deviceType'])) ||(!isset($post['deviceToken'])) || 
            ($post['isSocial'] == "") || ($post['email'] == "") || ($post['deviceType'] == "") ||($post['deviceToken'] == "")) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            if(($post['isSocial'] == 0)  && (!isset($post['password']) || ($post['password'] == ""))){
                $response = array('success' => 0, 'message' => 'Password Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            
            $user = User::where('email', $post['email'])->first();
            if (empty($user)) {
                $arr = array('success' => 0, 'message' => 'Invalid email or password.');
                echo json_encode($arr, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
                exit;
            } else {
                if ($user->status == 0) {
                    $arr = array('success' => 0, 'message' => 'User is blocked.');
                    echo json_encode($arr, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
                    exit;
                }
                elseif ($user->is_deleted == 1) {
                    $arr = array('success' => 0, 'message' => 'User is deleted.');
                    echo json_encode($arr, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
                    exit;
                } elseif (($post['isSocial'] == 0 && !Hash::check($post["password"], $user->password)) || ($post['isSocial'] == 1 && $user->is_social == 0)) {
                    $arr = array('success' => 0, 'message' => 'Invalid email or password.');
                    echo json_encode($arr, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
                    exit;
                } elseif ($user->status== 1) {
                    $loginToken=substr(str_shuffle($user->id.'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 20);
                    $user->login_token = $loginToken;
                    $user->device_type = $post['deviceType'];
                    $user->device_token = $post['deviceToken'];
                    $user->login_token = $loginToken;
                    $user->save();
                    $userData=$this->userDetailResponse($user);
                    $response = array('success' => 1, 'message' => 'Login Successfully' ,'result' => $userData);
                    echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                    exit;
                } else {
                    $response = array('success' => 0, 'message' => 'Email Or Password Invalid');
                    echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                    exit;
                }
            }
        } catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }

    public function forgotPassword()
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        
        try {
            if ((!isset($post['email'])) || (empty($post['email']))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $user = User::where('email', $post['email'])->first();

            if (!empty($user)) {
                $passwordResetCode=substr(str_shuffle($user->id.'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 20);
                Mail::send('emailtempletes.forgot_password', compact('user','passwordResetCode'), function($m) use($user) {
                    $m->to($user->email, $user->name)
                    ->subject("Forgot Password");
                });

                $user = User::find($user->id);
                $user->passwordResetCode = $passwordResetCode;
                $user->save();
                $response = array();
                $response['success'] = 1;
                $response['message'] = 'Password Reset Link Sent To E-mail Successfully.';
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $response = array();
                $response['success'] = 0;
                $response['message'] = 'Email not registered with the system.';
                echo json_encode($response);
                exit;
            }
        } catch (Exception $e) {
            $response = array();
            $response['success'] = 0;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
            exit;
        }
    }
        
    
    
    public function editProfile(Request $request)  //api edit profile
    {
        $post = $request->all();
        $file = $request->file('profilePic');
        $decode = json_decode($post['json_content']);
        try {
            if ((!isset($decode->id)) || (!isset($decode->name)) || (!isset($decode->email)) || (!isset($decode->phone))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            if ((empty($decode->id)) || (empty($decode->name)) || (empty($decode->email)) || (empty($decode->phone))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $checkEmail = User::where('email', $decode->email)->where('id', '!=', $decode->id)->first();
            if (!empty($checkEmail)) {
                $response = array('success' => 0, 'message' => 'This Email Already Exists');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $userUpdate = User::find($decode->id);
            $userUpdate->name = $decode->name;
            $userUpdate->email= $decode->email;
            $userUpdate->phone= $decode->phone;
            if (!empty($file)) {
                $file = $request->file('profilePic');
                $image_name = str_replace(' ', '-', $file->getClientOriginalName());
                $picture = time() . "." . $image_name;
                $destinationPath = public_path('profile_pic/');
                $file->move($destinationPath, $picture);
                $userUpdate->profile_link = 0;
                $userUpdate->profile_pic = $picture;
            }
            $userUpdate->save();
            $userData=[];
            $userData['id']=$userUpdate->id;
            $userData['name']=$userUpdate->name ? $userUpdate->name : '';
            $userData['email']=$userUpdate->email ? $userUpdate->email : '';
            $userData['phone']=$userUpdate->phone ?  $userUpdate->phone : '';
            if($userUpdate->profile_link == 0){
                $userData['profile_pic'] = $userUpdate->profile_pic != '' ? file_exists_in_folder('profile_pic', $userUpdate->profile_pic) : file_exists_in_folder('profile_pic', '');
            }
            else{
                $userData['profile_pic'] = $userUpdate->profile_pic;
            }
            
            $response = array('success' => 1, 'message' => 'Profile Updated Succeessfully','result' => $userData);
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        } catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }

    public function changePassword(Request $request) // api change password
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);  
        try {
            if ((!isset($post['id'])) || (empty($post['id'])) || (!isset($post['oldPassword'])) || (empty($post['oldPassword'])) || (!isset($post['newPassword'])) || (empty($post['newPassword']))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $user = User::find($post['id']);
            if(!Hash::check($post["oldPassword"], $user->password)){
                $response = array('success' => 0, 'message' => 'Old Password is wrong');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            if (!empty($user)) {
                $user->password =  Hash::make($post['newPassword']);
                $user->save();
                $response = array('success' => 1, 'message' => 'Password Updated Successfully');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            } else {
                $response = array('success' => 0, 'message' => 'Password Not Update');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
        } catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }
    public function getProfileDetails()
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        
        try {
            if ((!isset($post['id'])) || (empty($post['id']))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $user = User::find($post['id']);     
            if(empty($user)){
                $response = array('success' => 0, 'message' => 'User Does not exists!');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }       
            $userData = $this->userDetailResponse($user);
            $response = array('success' => 1, 'message' => 'Profile Detail get Succeessfully','result' => $userData);
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
        catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }
}