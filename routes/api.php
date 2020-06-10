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
