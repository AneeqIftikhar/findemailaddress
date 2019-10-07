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

Route::post('test_fastspring','EmailController@test_fastspring');

Route::post('get_webhook','WebhookController@get_webhook');


Route::middleware('LimitRequestAPI')->group(function () {
	
    Route::post('find_email_api','API\EmailApiController@find_email_api');
	Route::post('verify_email_api','API\EmailApiController@verify_email_api');
});

