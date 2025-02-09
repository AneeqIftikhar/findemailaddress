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


Route::post('update_emails','API\BulkApiController@update_emails');
Route::post('fetch_file_emails','API\BulkApiController@fetch_file_emails');
Route::post('fetch_unprocessed_files','API\BulkApiController@fetch_unprocessed_files');

Route::get('invalid_domains','API\EmailApiController@invalid_domains_api');



Route::middleware('cors')->group(function () {

    Route::post('get_emails_api','API\EmailApiController@get_emails_api');
	Route::post('add_emails_api','API\EmailApiController@add_emails_api');
	Route::post('failed_response_notification','API\EmailApiController@failed_response_notification');
    Route::post('admin_login','Admin\AdminController@login');
    Route::get('admin_get_users','Admin\AdminController@get_users');

    Route::middleware(['verify.api_token'])->group(function () {
        Route::post('verify/email','API\EmailApiController@public_verify_email_api');
        Route::post('find/email','API\EmailApiController@public_find_email_api');
    });

});


Route::get('testAutomizy','UserController@testAutomizy');
Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
