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

Auth::routes(['verify' => true]);


Route::get('/', function () {
    // return view('auth/login');
    return Redirect::to(env('APP_HOME_ADDRESS', 'https://findemailaddress.co'));
});




//Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::get('/find', 'EmailController@find_email_page')->name('find')->middleware('verified');
Route::get('/verify', 'EmailController@verify_email_page')->name('verify')->middleware('verified');


Route::get('find-old', function()
{
    return view('find-old');
})->name('find-old')->middleware('verified');

Route::get('verify-old', function()
{
    return view('verify-old');
})->name('verify-old')->middleware('verified');
Route::get('bulk_find', function()
{
    return view('bulk_find');
})->name('bulk_find')->middleware('verified');
Route::get('bulk_verify', function()
{
    return view('bulk_verify');
})->name('bulk_verify')->middleware('verified');
Route::get('usage_policy', function()
{
    return view('usage_policy');
})->name('usage_policy');

Route::get('account_settings', function()
{
    return view('account_settings');
})->name('account_settings')->middleware('verified');



Route::get('contact_form', function()
{
    return view('contact_form');
})->name('contact_form')->middleware('verified');

Route::get('subscriptions','UserController@getUserSubscriptions')->name('subscriptions')->middleware('verified');
Route::get('upgrade_account', 'UserController@getUpgradeAccount')->name('upgrade_account')->middleware('verified');

Route::get('find_history','EmailController@getUserFoundEmails')->name('find_history')->middleware('verified');
Route::get('verify_history','EmailController@getUserVerifiedEmails')->name('verify_history')->middleware('verified');
Route::get('list','EmailController@getUserFiles')->name('list')->middleware('verified');
Route::get('emails/{id}','EmailController@getEmailsFromFile')->name('emails')->middleware('verified');

Route::post('batch','EmailController@import')->name('batch')->middleware('verified');

Route::get('downloadcsv/{id}/{type}/{records}','EmailController@downloadExcel')->name('downloadcsv')->middleware('verified');


Route::get('downloadfoundrecords/{type}/{records}','EmailController@downloadFoundRecords')->name('downloadfoundrecords')->middleware('verified');
Route::get('downloadverifiedrecords/{type}/{records}','EmailController@downloadVerifiedRecords')->name('downloadverifiedrecords')->middleware('verified');


Route::post('update_personal_info','UserController@update_personal_info')->middleware('verified');
Route::post('update_password','UserController@update_password')->middleware('verified');


Route::get('2checkout','UserController@test_2checkout');
Route::post('handleIpn','UserController@handleIpn');
Route::get('return_url','UserController@return_url');

Route::post('disableRecurringBilling','UserController@disableRecurringBilling')->name('disableRecurringBilling')->middleware('verified');
Route::post('enableRecurringBilling','UserController@enableRecurringBilling')->name('enableRecurringBilling')->middleware('verified');
Route::post('report_bounce','EmailController@report_bounce')->name('report_bounce')->middleware('verified');



Auth::routes();


Route::group(['middleware' => ['throttle:8']], function () {
    Route::post('find_email','EmailController@find_email_ajax');
    Route::post('verify_email','EmailController@verify_email_ajax');
});



Route::post('webhook','SubscriptionController@webhook');