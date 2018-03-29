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


Route::post('/register' , 'UserController@register');
Route::post('/login' , 'UserController@login');
Route::post('/logout' , 'UserController@logout');
Route::get('/index' , 'UserController@index');
Route::get('/articlebycategory/{category}' , 'UserController@article_by_category');
Route::get('/articlebykeyword/{keyword}' , 'UserController@article_by_keyword');
Route::get('/keywords' , 'UserController@keywords');
Route::get('/search/{search}' , 'UserController@search');
Route::get('/mysearchs'  , 'UserController@mysearchs');
Route::get('/myprofile' , 'UserController@myprofile');
Route::post('/comment' , 'UserController@comment_article');
Route::post('/reply' , 'UserController@reply_comment');
Route::post('/report' , 'UserController@report_comment');
Route::post('/changepass' , 'UserController@change_password');
Route::post('/share/{article_id}' , 'UserController@share_article');
Route::post('/like/{article_id}' , 'UserController@like_article');
Route::post('likecomment/{comment_id}' , 'UserController@like_comment');
Route::post('likereply/{reply_id}' , 'UserController@like_reply');
Route::post('dislikereply/{reply_id}' , 'UserController@dislike_reply');
Route::post('dislikecomment/{comment_id}' , 'UserController@dislike_comment');
Route::post('dislikearticle/{article_id}' , 'UserController@dislike_article');
Route::get('/myshares' , 'UserController@myshares');
Route::post('/bookmark/{article_id}' , 'UserController@bookmark');
Route::get('/mybookmarks' , 'UserController@mybookmarks');
Route::post('/contact' , 'UserController@contact');
Route::get('/about' , 'UserController@about');
Route::get('/notifications' , 'UserController@mynotifications');
Route::get('/article/{article_id}' , 'UserController@article');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

