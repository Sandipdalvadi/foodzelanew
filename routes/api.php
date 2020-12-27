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
Route::post('changePassword', 'API\WebservicesController@changePassword')->middleware('api_row');
Route::post('editProfile', 'API\WebservicesController@editProfile')->middleware('api_form');
Route::post('getProfileDetails', 'API\WebservicesController@getProfileDetails')->middleware('api_row');
Route::post('phoneResetPassword', 'API\WebservicesController@phoneResetPassword');
Route::post('categoriesList', 'API\WebservicesController@categoriesList');
Route::post('addRestaurent', 'API\WebservicesController@addRestaurent')->middleware('api_form');
Route::post('restaurentOwnerDocVerified', 'API\WebservicesController@restaurentOwnerDocVerified')->middleware('api_form');
Route::post('addBankAccount', 'API\WebservicesController@addBankAccount')->middleware('api_row');
Route::post('bankList', 'API\WebservicesController@bankList')->middleware('api_row');
