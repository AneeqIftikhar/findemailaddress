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



Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::get('find', function()
{
    return view('find');
})->name('find')->middleware('verified');
Route::get('verify', function()
{
    return view('verify');
})->name('verify')->middleware('verified');
Route::get('batch_find', function()
{
    return view('batch_find');
})->name('batch_find')->middleware('verified');
Route::get('batch_verify', function()
{
    return view('batch_verify');
})->name('batch_verify')->middleware('verified');
Route::get('usage_policy', function()
{
    return view('usage_policy');
})->name('usage_policy');

Route::get('account_settings', function()
{
    return view('account_settings');
})->name('account_settings')->middleware('verified');

Route::get('find_history','EmailController@getUserFoundEmails')->name('find_history')->middleware('verified');
Route::get('verify_history','EmailController@getUserVerifiedEmails')->name('verify_history')->middleware('verified');
Route::get('list','EmailController@getUserFiles')->name('list')->middleware('verified');
Route::get('emails/{id}','EmailController@getEmailsFromFile')->name('emails')->middleware('verified');
Route::post('find_email','EmailController@find_email_ajax')->middleware('verified');
Route::post('verify_email','EmailController@verify_email_ajax')->middleware('verified');
Route::post('batch','EmailController@import')->name('batch')->middleware('verified');

Route::get('downloadcsv/{id}/{type}/{records}','EmailController@downloadExcel')->name('downloadcsv')->middleware('verified');


Route::get('downloadfoundrecords/{type}/{records}','EmailController@downloadFoundRecords')->name('downloadfoundrecords')->middleware('verified');
Route::get('downloadverifiedrecords/{type}/{records}','EmailController@downloadVerifiedRecords')->name('downloadverifiedrecords')->middleware('verified');


Route::post('update_personal_info','UserController@update_personal_info')->middleware('verified');
Route::post('update_password','UserController@update_password')->middleware('verified');
