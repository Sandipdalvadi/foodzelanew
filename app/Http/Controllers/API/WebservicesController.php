<?php 

namespace App\Http\Controllers\API;

use Validator, DB, Hash, Auth, Carbon, Session, Lang, App, URL;
use App\User;
use App\Models\Restaurents;
use App\Models\Categories;
use App\Models\RestaurentsOwnerDetail;
use App\Models\SiteSettings;
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
            // if((!isset($post['name'])) || (!isset($post['email'])) || (!isset($post['role'])) || (!isset($post['phone'])) || (!isset($post['isSocial'])) || (!isset($post['isEmailVerified']))
            // || (!isset($post['isPhoneVerified'])) || (!isset($post['deviceToken'])) || (!isset($post['deviceType'])) || (!isset($post['isActive'])) || (!isset($post['languageCode'])) || 
            // ($post['name'] =="") || ($post['role'] =="") ||  ($post['isSocial'] =="") || ($post['isEmailVerified'] =="") || 
            // ($post['isPhoneVerified'] =="") || ($post['deviceToken'] =="") || ($post['isActive'] =="")|| ($post['languageCode'] =="") )
            // {
            //     $response = array('success' => 0, 'message' => 'All Fields Are Required');
            //     echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            //     exit;
            // }
            // if(($post['isSocial'] == 0) && ((!isset($post['password']) || ($post['password'] =="") || ($post['phone'] =="")) || ($post['email'] ==""))){
            //     $response = array('success' => 0, 'message' => 'All Fields Are Required');
            //     echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            //     exit;
            // } 
            // elseif(($post['isSocial'] == 1) && ((!isset($post['socialType'])) || (!isset($post['socialId'])) || ($post['socialType'] =="") || ($post['socialId'] =="")))
            // {
            //     $response = array('success' => 0, 'message' => 'All Fields Are Required');
            //     echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            //     exit;
            // }
            $checkEmail = [];
            if($post['email'] != ""){
                $checkEmail = User::where('email',$post['email'])->first();
            }
            elseif($post['phone'] != ""){
                $checkEmail = User::where('phone',$post['phone'])->first();
            }
            if (!empty($checkEmail) && $post['isSocial'] == 0) {
                $response = array('success' => 0, 'message' => 'This Email or phone Email Already Exists');
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
            $newUser->status = $post['isActive']; 
            $newUser->language_code = $post['languageCode']; 
            $newUser->address = $post['address']; 
            $newUser->latitude = $post['latitude']; 
            $newUser->longitude = $post['longitude']; 
            
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
        $userData['id']=(string)$user->id;
        $userData['name'] = $user->name ? $user->name : '';
        $userData['email'] = $user->email ? $user->email : '';
        $userData['role'] = (string)$user->role ? $user->role : '';
        $userData['phone'] = (string)$user->phone ? $user->phone : '';
        if($user->profile_link == 1){
            $userData['profilePic'] = $user->profile_pic ? $user->profile_pic : '';
        }
        else{
            $userData['profilePic'] = file_exists_in_folder('profile_pic',$user->profile_pic);
        }
        $userData['isSocial'] = (string)$user->is_social ? $user->is_social : "0";
        $userData['socialType'] = (string)$user->social_type ? $user->social_type : '';
        $userData['socialId'] = $user->social_id ? $user->social_id : '';
        $userData['isEmailVerified'] = (string)$user->is_email_verified ? $user->is_email_verified : "0";
        $userData['isPhoneVerified'] = (string)$user->is_phone_verified ? $user->is_phone_verified : "0";
        $userData['deviceToken'] = $user->device_token ? $user->device_token : '';
        $userData['deviceType'] = $user->device_type ? $user->device_type : "1";
        $userData['loginToken'] = $user->login_token ? $user->login_token : '';
        $userData['instagram'] = $user->instagram ? $user->instagram : ''; 
        $userData['snap'] = $user->snap ? $user->snap : '';
        $userData['languageCode'] = $user->language_code ? $user->language_code : '';
        $userData['address'] = $user->address ? $user->address : '';
        $userData['latitude'] = $user->latitude ? $user->latitude : '';
        $userData['longitude'] = $user->longitude ? $user->longitude : '';
        if($user->role == 2){
            $ownerDetail = $user->hasOneRestaurentsOwnerDetail ? $user->hasOneRestaurentsOwnerDetail : [];
            $userData['is_document_verified'] = $ownerDetail ? $ownerDetail->is_document_verified : 0;
            if($ownerDetail){
                $userData['liceneseDelivery'] = $ownerDetail->licenese_delivery ? file_exists_in_folder('liceneseDelivery', $ownerDetail->licenese_delivery)  : file_exists_in_folder('liceneseDelivery', '');
                $userData['certificationShop'] = $ownerDetail->certification_shop ? file_exists_in_folder('certificationShop', $ownerDetail->certification_shop)  : file_exists_in_folder('certificationShop', '');
                $userData['idProof'] = $ownerDetail->id_proof ? file_exists_in_folder('idProof', $ownerDetail->id_proof)  : file_exists_in_folder('idProof', '');
            }
        }
        return $userData;
    } 
  
    public function login(Request $request)
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        
        try {
            if ((!isset($post['isSocial'])) || (!isset($post['email'])) || (!isset($post['phone'])) || (!isset($post['deviceType'])) ||(!isset($post['deviceToken'])) || 
            ($post['isSocial'] == "") || ($post['deviceType'] == "") ||($post['deviceToken'] == "")) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            if(($post['isSocial'] == 0)  && (!isset($post['password']) || ($post['password'] == ""))){
                $response = array('success' => 0, 'message' => 'Password Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            if($post['email'] != ""){
                $user = User::where('email', $post['email'])->first();
            }
            elseif($post['phone'] != ""){
                $user = User::where('phone', $post['phone'])->first();
            }
            if (empty($user)) {
                $arr = array('success' => 0, 'message' => 'Invalid email or phone or password.');
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
                    // echo "<pre>";
                    // print_r($user);
                    // exit;
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
            $userUpdate->email = $decode->email;
            $userUpdate->phone = $decode->phone;
            $userUpdate->address = $decode->address; 
            $userUpdate->latitude = $decode->latitude; 
            $userUpdate->longitude = $decode->longitude; 
            if($decode->password != ""){
                $userUpdate->password = $decode->password;
            }
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
            $userData=$this->userDetailResponse($userUpdate);
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

    public function phoneResetPassword()
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        
        try {
            if ((!isset($post['phone'])) || (!isset($post['password'])) || (empty($post['phone'])) || (empty($post['password']))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $user = User::where('phone',$post['phone'])->first();     
            if(empty($user)){
                $response = array('success' => 0, 'message' => 'User Does not exists!');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }       
            $user->password = Hash::make($post['password']);
            $user->save();
            $userData = $this->userDetailResponse($user);
            $response = array('success' => 1, 'message' => 'Password updated Succeessfully','result' => $userData);
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
        catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }

    public function categoriesList()
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        
        try {
            $limit = isset($post['startLimit']) ? $post['startLimit'] : 0;
            $categories = Categories::where('is_deleted',0)->where('status',1)->limit(10)->offset($limit)->orderBy('id', 'desc')->get();     
            $totalCount = Categories::where('is_deleted',0)->where('status',1)->count();     
            
            if(count($categories))
            {
                $responses = [];
                foreach($categories as $category)
                {
                    $response = [];
                    $response['id'] = $category->id;
                    $response['name_en'] = $category->name_en ? $category->name_en : '';
                    $response['name_ar'] = $category->name_ar ? $category->name_ar : '';
                    $response['image'] = $category->image != '' ? file_exists_in_folder('categories', $category->image) : file_exists_in_folder('categories', '');
                    $responses[] = $response;
                }
                $response = array('success' => 1 ,'message' => 'Category list loaded successfully.','totalCount'=>$totalCount, 'result' => $responses);
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }
            else
            {
                $response = array('success' => 0, 'message' => 'No category found');
                 echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }
        }
        catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }

    public function addRestaurent(Request $request)  //api edit profile
    {
        $post = $request->all();
        $ownerLogo = $request->file('ownerLogo');
        $decode = json_decode($post['json_content']);
        try {
            if ((!isset($decode->userId)) || (!isset($decode->name))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            if ((empty($decode->userId)) || (empty($decode->name))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $restaurent = new Restaurents;
            $restaurent->user_id = $decode->userId;
            $restaurent->name = $decode->name;
            $restaurent->status = 2;
            $restaurent->is_deleted = 0;
            if (!empty($ownerLogo)) {
                $file = $ownerLogo;
                $image_name = str_replace(' ', '-', $file->getClientOriginalName());
                $picture = time() . "." . $image_name;
                $destinationPath = public_path('ownerLogo/');
                $file->move($destinationPath, $picture);
                $restaurent->owner_logo = $picture;
            }
            $restaurent->save();
            $result['id'] = $restaurent->id;
            $result['name'] = $restaurent->name ? $restaurent->name : '';
            $result['ownerLogo'] = $restaurent->owner_logo ? file_exists_in_folder('ownerLogo', $restaurent->owner_logo)  : file_exists_in_folder('ownerLogo', '');
            
            $response = array('success' => 1, 'message' => 'Restaurent Added Succeessfully','result' => $result);
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        } catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }

    public function restaurentOwnerDocVerified(Request $request)  //api edit profile
    {
        $post = $request->all();
        $liceneseDelivery = $request->file('liceneseDelivery');
        $certificationShop = $request->file('certificationShop');
        $idProof = $request->file('idProof');
        $decode = json_decode($post['json_content']);
        try {
            if ((!isset($decode->restaurentOwnerId)) || (empty($decode->restaurentOwnerId))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            if(empty($liceneseDelivery)){
                $response = array('success' => 0, 'message' => 'Licence is Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            if(empty($certificationShop)){
                $response = array('success' => 0, 'message' => 'Shop certificate Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            if(empty($idProof)){
                $response = array('success' => 0, 'message' => 'Id Proof Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            
            $restaurentsOwnerDetail = new RestaurentsOwnerDetail;
            $restaurentsOwnerDetail->restaurent_owner_id = $decode->restaurentOwnerId;
            if (!empty($liceneseDelivery)) {
                $file = $liceneseDelivery;
                $image_name = str_replace(' ', '-', $file->getClientOriginalName());
                $picture = time() . "." . $image_name;
                $destinationPath = public_path('liceneseDelivery/');
                $file->move($destinationPath, $picture);
                $restaurentsOwnerDetail->licenese_delivery = $picture;
            }
            if (!empty($certificationShop)) {
                $file = $certificationShop;
                $image_name = str_replace(' ', '-', $file->getClientOriginalName());
                $picture = time() . "." . $image_name;
                $destinationPath = public_path('certificationShop/');
                $file->move($destinationPath, $picture);
                $restaurentsOwnerDetail->certification_shop = $picture;
            }
            if (!empty($idProof)) {
                $file = $idProof;
                $image_name = str_replace(' ', '-', $file->getClientOriginalName());
                $picture = time() . "." . $image_name;
                $destinationPath = public_path('idProof/');
                $file->move($destinationPath, $picture);
                $restaurentsOwnerDetail->id_proof = $picture;
            }
            
            $restaurentsOwnerDetail->is_document_verified = 1;
            $restaurentsOwnerDetail->save();
            $user = User::with('hasOneRestaurentsOwnerDetail')->find($restaurentsOwnerDetail->restaurent_owner_id);
            $result= $this->userDetailResponse($user);
            $response = array('success' => 1, 'message' => 'Document verified Succeessfully','result' => $result);
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        } catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }   

    public function termsCondition(){
        try {
            $siteSetting = SiteSettings::first();
            
            $input = file_get_contents('php://input');
            $post = json_decode($input, true);
            $lang = isset($post['lang']) ? $post['lang'] : 'ar';
            if ($lang == 'en') {
                return view('render_api', ['data'=>$siteSetting->terms_conditions_en]);
            }
            else{
                return view('render_api', ['data'=>$siteSetting->terms_conditions_ar]);
            }
        } catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }

    public function aboutUs(){
        try {
            $siteSetting = SiteSettings::first();
            
            $input = file_get_contents('php://input');
            $post = json_decode($input, true);
            $lang = isset($post['lang']) ? $post['lang'] : 'ar';
            if ($lang == 'en') {
                return view('render_api', ['data'=>$siteSetting->about_us_en]);
            }
            else{
                return view('render_api', ['data'=>$siteSetting->about_us_ar]);
            }
        } catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }
}