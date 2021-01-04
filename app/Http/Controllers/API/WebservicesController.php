<?php 

namespace App\Http\Controllers\API;

use Validator, DB, Hash, Auth, Session, Lang, App, URL;
use App\User;
use App\Models\Restaurents;
use App\Models\Categories;
use App\Models\RestaurentsOwnerDetail;
use App\Models\SiteSettings;
use App\Models\BankAccount;
use App\Models\BankList;
use App\Models\RestaurentCategory;
use App\Models\Foods;
use App\Models\FoodsImages;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
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
            $newUser->address = isset($post['address']) ? $post['address'] : ''; 
            $newUser->latitude = isset($post['latitude']) ? $post['latitude'] : ''; 
            $newUser->longitude = isset($post['longitude']) ? $post['longitude'] : ''; 
            
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
            $userData['is_document_verified'] = $ownerDetail ? $ownerDetail->is_document_verified : false;
            $userData['is_document_verified'] = $userData['is_document_verified'] == 1 ? true : false;
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
                } elseif ($user->status== 1 || $user->status== 2) {
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
                $response = array('success' => 0, 'message' => 'Phone number not registered!');
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
                $response = array('success' => 0, 'message' => 'Category not found.');
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
            if ((!isset($decode->userId)) || (!isset($decode->name)) || (!isset($decode->description )) || (!isset($decode->address)) || (!isset($decode->latitude)) || (!isset($decode->longitude)) || (!isset($decode->phone)) || (!isset($decode->deliveryFee)) || (!isset($decode->adminCommission)) || (!isset($decode->categories)) || (!isset($decode->openTime)) || (!isset($decode->closeTime))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            if ((empty($decode->userId)) || (empty($decode->name))|| (empty($decode->description )) || (empty($decode->address)) || (empty($decode->latitude)) || (empty($decode->longitude)) || (empty($decode->phone)) || (empty($decode->deliveryFee)) || (empty($decode->adminCommission)) || (empty($decode->openTime)) || (empty($decode->closeTime))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $restaurent = new Restaurents;
            $restaurent->user_id = $decode->userId;
            $restaurent->name = $decode->name;
            $restaurent->description = $decode->description;
            $restaurent->address = $decode->address;
            $restaurent->latitude = $decode->latitude;
            $restaurent->longitude = $decode->longitude;
            $restaurent->phone = $decode->phone;
            $restaurent->delivery_fee = $decode->deliveryFee;
            $restaurent->admin_commission = $decode->adminCommission;
            $restaurent->open_time = $decode->openTime;
            $restaurent->close_time = $decode->closeTime;
            $restaurent->is_open = $decode->isOpen;
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
            if(count($decode->categories)){
                foreach($decode->categories as $category){
                    $restaurentCategory = new RestaurentCategory;
                    $restaurentCategory->restaurent_id = $restaurent->id;
                    $restaurentCategory->category_id = $category;
                    $restaurentCategory->save();
                }
            }
            $result['id'] = $restaurent->id;
            $result['name'] = $restaurent->name ? $restaurent->name : '';
            $result['description'] = $restaurent->description ? $restaurent->description : '';
            $result['address'] = $restaurent->address ? $restaurent->address : '';
            $result['latitude'] = $restaurent->latitude ? $restaurent->latitude : '';
            $result['longitude'] = $restaurent->longitude ? $restaurent->longitude : '';
            $result['phone'] = $restaurent->phone ? $restaurent->phone : '';
            $result['deliveryFee'] = $restaurent->delivery_fee ? $restaurent->delivery_fee : '';
            $result['adminCommission'] = $restaurent->admin_commission ? $restaurent->admin_commission : '';
            $result['openTime'] = $restaurent->open_time ? $restaurent->open_time : '';
            $result['closeTime'] = $restaurent->close_time ? $restaurent->close_time : '';
            $result['isOpen'] = $restaurent->is_open ? $restaurent->is_open : false;
            $result['adminCommission'] = $restaurent->admin_commission ? $restaurent->admin_commission : '';
            $result['ownerLogo'] = $restaurent->owner_logo ? file_exists_in_folder('ownerLogo', $restaurent->owner_logo)  : file_exists_in_folder('ownerLogo', '');
            $restaurentCategories = RestaurentCategory::with('hasOneCategory')->where('restaurent_id',$restaurent->id)->get();
            $result['categories'] = [];
            foreach($restaurentCategories as $restaurentCategory){
                $category = [];
                $categoryObj = $restaurentCategory->hasOneCategory ? $restaurentCategory->hasOneCategory : '';
                if(!empty($categoryObj)){
                    $category['id'] = $categoryObj ? $categoryObj->id : '';
                    $category['nameAr'] = $categoryObj ? $categoryObj->name_ar : '';
                    $category['nameEn'] = $categoryObj ? $categoryObj->name_en : '';
                    $category['image'] = $categoryObj ? file_exists_in_folder('categories', $categoryObj->image)  : file_exists_in_folder('categories', '');
                    $result['categories'][] = $category;
                }
            }
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

    public function termsCondition(Request $request){
        $siteSetting = SiteSettings::first();
        $lang = $request->lang ? $request->lang : 'ar';
        if ($lang == 'en') {
            return view('render_api', ['data'=>$siteSetting->terms_conditions_en]);
        }
        else{
            return view('render_api', ['data'=>$siteSetting->terms_conditions_ar]);
        }
    }

    public function aboutUs(Request $request){
        $siteSetting = SiteSettings::first();
        $lang = $request->lang ? $request->lang : 'ar';
        if ($lang == 'en') {
            return view('render_api', ['data'=>$siteSetting->about_us_en]);
        }
        else{
            return view('render_api', ['data'=>$siteSetting->about_us_ar]);
        }
    }
    
    public function addBankAccount()
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        
        try {
            if ((!isset($post['name'])) || (!isset($post['userId'])) || (!isset($post['bankId'])) || (!isset($post['accountNumber'])) || (!isset($post['city'])) || (empty($post['name'])) || (empty($post['bankId'])) || (empty($post['accountNumber']))|| (empty($post['city']))  || (empty($post['userId']))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $bankAccount = new BankAccount;
            $bankAccount->user_id = $post['userId'];
            $bankAccount->name = $post['name'];
            $bankAccount->bank_id = $post['bankId'];
            $bankAccount->account_number = $post['accountNumber'];
            $bankAccount->city = $post['city'];
            $bankAccount->save();
            $result = [];
            $bankList = BankList::find($bankAccount->bank_id);
            $result['id'] = $bankAccount->id ? $bankAccount->id : '';
            $result['name'] = $bankAccount->name ? $bankAccount->name : '';
            $result['bankName'] = $bankList ? $bankList->name : '';
            $result['bankId'] = $bankList ? $bankList->id : '';
            $result['accountNumber'] = $bankAccount->account_number ? $bankAccount->account_number : '';
            $result['city'] = $bankAccount->city ? $bankAccount->city : '';

            $response = array('success' => 1 ,'message' => 'Bank account saved successfully.', 'result' => $result);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            
        }
        catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }
    public function bankList()
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        
        try {
            $bankLists = BankList::where('status',1)->get();
            $responses = [];
            if(count($bankLists)){
                foreach($bankLists as $bankList){
                    $response = [];
                    $response['id'] = $bankList->id;
                    $response['name'] = $bankList->name ? $bankList->name : '';
                    $responses[] = $response;
                }
                $response = array('success' => 1 ,'message' => 'Bank list loaded successfully.', 'result' => $responses);
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }
            else{
                $response = array('success' => 0, 'message' => 'Bank not found yet.');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);    
            }
        }
        catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }
    public function restaurentListByOwner()
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        
        try {
            if ((!isset($post['userId'])) || (empty($post['userId']))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $restaurents = Restaurents::where('user_id',$post['userId'])->where('is_deleted',0)->where('status',1)->get();
            $responses = [];
            if(count($restaurents)){
                foreach($restaurents as $restaurent){
                    $response = [];
                    $response['id'] = $restaurent->id;
                    $response['name'] = $restaurent->name ? $restaurent->name : '';
                    $response['description'] = $restaurent->description ? $restaurent->description : '';
                    $response['image'] = $restaurent->owner_logo ? file_exists_in_folder('ownerLogo', $restaurent->owner_logo) : file_exists_in_folder('ownerLogo', '');
                    $response['openTime'] = $restaurent->open_time ? $restaurent->open_time : '';
                    $response['closeTime'] = $restaurent->close_time ? $restaurent->close_time : '';
                    $response['isOpen'] = is_open_restaurent($response['openTime'], $response['closeTime']);
                    $response['rating'] = 4.5;
                    $responses[] = $response;
                }
                $response = array('success' => 1 ,'message' => 'Restaurents loaded successfully.', 'result' => $responses);
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }
            else{
                $response = array('success' => 0, 'message' => 'Restaurents not found yet.');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);    
            }
        }
        catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }

    public function addFood(Request $request)  //api edit profile
    {
        $post = $request->all();
        $images = $request->file('images');
        $decode = json_decode($post['json_content']);
        try {
            if ((!isset($decode->nameAr)) || (empty($decode->nameAr)) || (!isset($decode->nameEn)) || (empty($decode->nameEn)) || (!isset($decode->price)) || (empty($decode->price)) || (!isset($decode->description)) || (!isset($decode->ingredients)) || (!isset($decode->weight)) || (!isset($decode->featured)) || (!isset($decode->restaurantId)) || (!isset($decode->categoryId))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            
            $foods = new Foods;
            $foods->name_ar = $decode->nameAr;
            $foods->name_en = $decode->nameEn;
            $foods->price = $decode->price;
            $foods->discount_price = $decode->discountPrice;
            $foods->description = $decode->description;
            $foods->ingredients = $decode->ingredients;
            $foods->weight = $decode->weight;
            $foods->featured = $decode->featured;
            $foods->restaurant_id = $decode->restaurantId;
            $foods->category_id = $decode->categoryId;
            $foods->status = $decode->isActive;
            $foods->save();
            if (count($images)) {
                // $file = $liceneseDelivery;response
                foreach($images as $file){
                    $foodsImages = new FoodsImages;
                    $image_name = str_replace(' ', '-', $file->getClientOriginalName());
                    $picture = time() . "." . $image_name;
                    $destinationPath = public_path('foods/');
                    $file->move($destinationPath, $picture);
                    $foodsImages->image = $picture;
                    $foodsImages->food_id = $foods->id;
                    $foodsImages->save();
                }
            }
            $response = $this->foodResponse($foods);
            
            $responses = array('success' => 1, 'message' => 'Food Succeessfully','result' => $response);
            echo json_encode($responses, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        } catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }
    public function foodResponse($foodObj){
        $response['id'] = $foodObj->id;
        $response['nameAr'] = $foodObj->name_ar ? $foodObj->name_ar : '';
        $response['nameEn'] = $foodObj->name_en ? $foodObj->name_en : '';
        $response['price'] = $foodObj->price ? $foodObj->price : 0;
        $response['discountPrice'] = $foodObj->discount_price ? $foodObj->discount_price : 0;
        $response['description'] = $foodObj->description ? $foodObj->description : '';
        $response['ingredients'] = $foodObj->ingredients ? $foodObj->ingredients : '';
        $response['weight'] = $foodObj->weight ? $foodObj->weight : 0;
        $response['featured'] = $foodObj->featured ? $foodObj->featured : 0;
        $response['restaurantId'] = $foodObj->restaurant_id ? $foodObj->restaurant_id : 0;
        $response['categoryId'] = $foodObj->category_id ? $foodObj->category_id : 0;
        $response['status'] = $foodObj->status ? $foodObj->status : 0;
        $categoryObj = $foodObj->hasOneCategory ? $foodObj->hasOneCategory : [];
        $response['nameEn'] = $categoryObj ? $categoryObj->name_en : '';
        $response['nameAr'] = $categoryObj ? $categoryObj->name_ar : '';
        $foodsImagesObjs = $foodObj->hasManyFoodsImages ? $foodObj->hasManyFoodsImages : [];
        $response['images'] = [];
        if(count($foodsImagesObjs)){
            foreach($foodsImagesObjs as $foodsImagesObj){
                $response['images'][] = $foodsImagesObj ? file_exists_in_folder('foods',$foodsImagesObj->image) : file_exists_in_folder('foods','');
            }
        }
        else{
            $response['images'][] = file_exists_in_folder('foods','');   
        }
        return $response;
    }
    public function foodList(){
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        
        try {
            if ((!isset($post['restaurantId'])) || (empty($post['restaurantId']))) {
                $response = array('success' => 0, 'message' => 'All Fields Are Required');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
                exit;
            }
            $foods = Foods::where('restaurant_id',$post['restaurantId'])->where('is_deleted',0)->where('status',1)->get();
            $responses = [];
            if(count($foods)){
                foreach($foods as $food){
                    $responses[] = $this->foodResponse($food);
                }
                $response = array('success' => 1 ,'message' => 'Foods loaded successfully.', 'result' => $responses);
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }
            else{
                $response = array('success' => 0, 'message' => 'Foods not found yet.');
                echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);    
            }
        }
        catch (Exception $e) {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);
            exit;
        }
    }
}
