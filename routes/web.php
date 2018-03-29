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

Route::get('/home', 'HomeController@index')->name('home');





Route::get('/admin/createarticle' , 'AdminController@create_article');
Route::post('/admin/storearticle' , 'AdminController@store_article');
Route::get('/admin/editarticle' , 'AdminController@edit_article');
Route::put('admin/updatearticle' , 'AdminController@update_article');
Route::delete('/admin/deletearticle/{id}' , 'AdminController@delete_article');
Route::get('/admin/categories' , 'AdminController@categories');
Route::post('/admin/storecategory' , 'AdminController@store_category');
Route::delete('/admin/deletecategory/{id}' , 'AdminController@delete_category');
Route::get('/admin/userslist' , 'AdminController@users_list');
Route::delete('/admin/deleteuser/{id}' , 'AdminController@delete_user');
Route::get('/admin/reports' , 'AdminController@view_reports');
Route::delete('/admin/deletereport' , 'AdminController@delete_report');
Route::get('/admin/comments' , 'AdminController@view_comments');
Route::delete('/admin/deletecomment/{id}' , 'AdminController@delete_comment');
Route::get('/admin/replies' , 'AdminController@view_replies');
Route::delete('/admin/deletereply/{id}', 'AdminController@delete_replies');
Route::get('/admin/messages' , 'AdminController@view_messages');
Route::delete('/admin/deletemessage/{id}' , 'AdminController@delete_message');
Route::get('/admin/notifications' , 'AdminController@notifications');
Route::get('/admin/mynotifications' , 'AdminController@mynotifications');
Route::get('/admin' , 'AdminController@index');
Route::get('/', function () {
    return view('welcome');
});
