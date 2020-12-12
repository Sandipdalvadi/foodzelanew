<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register', 'API\WebservicesController@register');
Route::post('login', 'API\WebservicesController@login');
Route::post('forgotPassword', 'API\WebservicesController@forgotPassword');
Route::post('changePassword', 'API\WebservicesController@changePassword');
Route::post('editProfile', 'API\WebservicesController@editProfile');
Route::post('getProfileDetails', 'API\WebservicesController@getProfileDetails');
Route::post('phoneResetPassword', 'API\WebservicesController@phoneResetPassword');
