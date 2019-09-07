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


Route::post('batch','EmailController@import');

Route::get('2checkout','UserController@test_2checkout');


Route::middleware('throttle:rate_limit,10,1440')->group(function () {
	Route::post('find_email_api','EmailController@find_email_api');
	Route::post('verify_email_api','EmailController@verify_email_api');
    
});

