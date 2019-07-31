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
    return view('welcome');
});

Auth::routes(['verify' => true]);

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

Route::get('list','EmailController@getUserFiles')->name('list')->middleware('verified');
Route::get('emails/{id}','EmailController@getEmailsFromFile')->name('emails')->middleware('verified');
Route::post('find_email','EmailController@find_email_ajax')->middleware('verified');
Route::post('verify_email','EmailController@verify_email_ajax')->middleware('verified');
Route::post('batch','EmailController@import')->name('batch')->middleware('verified');

Route::get('downloadcsv/{id}/{type}/{records}','EmailController@downloadExcel')->name('downloadcsv')->middleware('verified');

