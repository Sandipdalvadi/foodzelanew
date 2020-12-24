<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
    // return view('welcome');
});
Route::get('termsCondition/{lang}', 'API\WebservicesController@termsCondition');
Route::get('aboutUs/{lang}', 'API\WebservicesController@aboutUs');

Auth::routes();

Route::get('/ForgotPassword/{id}/{token}','HomeController@forgotPwd');
Route::post('/passwordChange1','HomeController@passwordChange1')->name('passwordChange1');
Route::get('/password_success','HomeController@password_success')->name('password_success');
Route::get('logout', 'Auth\LoginController@logout');


Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware'=>'admin','prefix'=>'admin'],function(){

    Route::get('home', 'HomeController@adminHome')->name('admin.home');
    Route::get('locale/{locale}', function ($locale){
        Session::put('locale', $locale);
        return redirect()->back();
    })->name('admin.lang');

    $paths = array(
        'managers'   => 'ManagersController',
        'permissions'   => 'PermissionsController',
        'categories'   => 'CategoriesController',
        'restaurents'   => 'RestaurentsController',
        'restaurent_owners'   => 'RestaurentOwnersController',
        'drivers'   => 'DriversController',
        'users'   => 'UsersController',
        
    );

    foreach($paths as $slug => $controller){
        Route::get('/'.$slug.'/index', $controller.'@index')->name('admin.'.$slug.'.index');
        Route::post('/'.$slug.'/list', $controller.'@list')->name('admin.'.$slug.'.list');
        Route::delete('/'.$slug.'/delete/{id}', $controller.'@destroy')->name('admin.'.$slug.'.delete');
        Route::get('/'.$slug.'/form/{id}', $controller.'@form')->name('admin.'.$slug.'.form');
        Route::post('/'.$slug.'/store/', $controller.'@store')->name('admin.'.$slug.'.save');
        Route::post('/'.$slug.'/alldeletes', $controller.'@alldeletes')->name('admin.'.$slug.'.alldelete');
        Route::get('/'.$slug.'/changeStatus/{id}', $controller.'@changeStatus')->name('admin.'.$slug.'.changeStatus');
    }
    Route::get('sitesetting/index', 'SiteSettingController@index')->name('admin.sitesetting.index');
    Route::post('sitesetting/save', 'SiteSettingController@save')->name('admin.sitesetting.save');
    Route::get('terms_conditions/index', 'TermsConditionsController@index')->name('admin.terms_conditions.index');
    Route::post('terms_conditions/save', 'TermsConditionsController@save')->name('admin.terms_conditions.save');
    Route::get('about_us/index', 'AboutUsController@index')->name('admin.about_us.index');
    Route::post('about_us/save', 'AboutUsController@save')->name('admin.about_us.save');
    Route::get('profile/index', 'ProfileController@index')->name('admin.profile.index');
    Route::post('profile/save', 'ProfileController@save')->name('admin.profile.save');
    
    
});