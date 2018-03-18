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

Auth::routes();

Route::get('/', 'HomeController@index');

Route::group(['middleware' => 'auth'], function() {
    Route::post('store-room', 'ChatRoomController@store');
});

Route::get('/chat-room/{id}', 'ChatRoomController@index');
Route::get('/privat-chat-room/{key}', 'ChatRoomController@goToPrivateChatRoom');
Route::get('messages/{id}', 'ChatRoomController@fetchMessages');
Route::post('messages/{id}', 'ChatRoomController@sendMessage');