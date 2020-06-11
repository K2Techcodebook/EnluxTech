<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



Route::group(['middleware' => 'guest'], function () {
  Route::post('/login', 'Auth\LoginController@login');
  Route::post('/register', 'Auth\RegisterController@register');

  Route::post('/admin/login', 'Auth\LoginController@login');
  Route::post('/admin/register', 'Auth\RegisterController@register');

//Airtime Vtu Request
  Route::post('/mtn-airtime-vtu-api', 'AirtimeVtuController@mtn_airtime_vtu_api');
  Route::post('/glo-airtime-vtu-api', 'AirtimeVtuController@glo_airtime_vtu_api');
  Route::post('/airtel-airtime-vtu-api', 'AirtimeVtuController@airtel_airtime_vtu_api');
  Route::post('/9mobile-airtime-vtu-api', 'AirtimeVtuController@etisalat_airtime_vtu_api');

  //GET VARIATION CODES
  Route::get('/get_variation_codes', 'DataSubscriptionController@get_variation_codes');

  //QUERY TRANSACTION STATUS
Route::post('/status', 'DataSubscriptionController@status');

//VERIFY SMILE EMAIL and PHONE and dstv card number
Route::post('/verify_smile_number', 'DataSubscriptionController@verify_smile_number');
Route::post('/verify_smile_email', 'DataSubscriptionController@verify_smile_email');
Route::post('/verify_smile_phone', 'DataSubscriptionController@verify_smile_phone');
Route::post('/verify_dstv_card_number', 'TvSubscriptionController@verify_dstv_card_number');
Route::post('/verify_gotv_card_number', 'TvSubscriptionController@verify_gotv_card_number');
Route::post('/verify_startime_card_number', 'TvSubscriptionController@verify_startime_card_number');

  //PURCHASE PRODUCT DATA BUNDLE
  Route::post('/mtn-data-vtu-api', 'DataSubscriptionController@mtn_data_vtu_api');
  Route::post('/glo-data-vtu-api', 'DataSubscriptionController@glo_data_vtu_api');
  Route::post('/airtel-data-vtu-api', 'DataSubscriptionController@airtel_data_vtu_api');
  Route::post('/9mobile-data-vtu-api', 'DataSubscriptionController@etisalat_data_vtu_api');
  Route::post('/smile-data-vtu-api', 'DataSubscriptionController@smile_data_vtu_api');



  //The TV Subscription
  Route::post('/dstv-sub-vtu-api', 'TvSubscriptionController@dstv_sub_vtu_api');
  Route::post('/gotv-sub-vtu-api', 'TvSubscriptionController@gotv_sub_vtu_api');
  Route::post('/startime-sub-vtu-api', 'TvSubscriptionController@startime_sub_vtu_api');

//The Educational PURCHASE
  Route::post('/waec-reg-vtu-api', 'EducationSubscriptionController@waec_reg_vtu_api');
Route::post('/waec-result-vtu-api', 'EducationSubscriptionController@waec_result_vtu_api');

});

Route::group(['middleware' => 'auth:api'], function () {
  Route::get('whoami', fn (Request $request) => $request->user());
  Route::get('logout', 'Auth\LoginController@logout');

  Route::apiResources([
    'users'           => 'UserCustomer',
    'metas'           => 'MetaController',
    'medias'          => 'MediaController',
  ]);
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth:api:admin'], function () {
  Route::get('whoami', fn (Request $request) => $request->user());
  Route::get('logout', 'Auth\LoginController@logout');

  Route::apiResources([
    'admins'           => 'Admin\AdminCustomer',
    'metas'            => 'MetaController',
    'medias'           => 'MediaController',
  ]);
});
