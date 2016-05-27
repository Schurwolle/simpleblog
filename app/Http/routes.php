<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('rss','RssController@rss');
Route::get('sitemap', 'SitemapController@generate');



Route::get('/', function () {
    return redirect('articles');
});





/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
Route::auth();

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
	]);

Route::get('login/facebook', 'Auth\AuthController@redirectToFacebook');
Route::get('login/facebook/callback', 'Auth\AuthController@getFacebook');

Route::post('unique', 'ArticlesController@unique');
Route::resource('articles', 'ArticlesController');
Route::get('articles/{articles}/favorite', 'ArticlesController@favorite');
Route::get('articles/{articles}/search/{query}', 'SearchController@show');


Route::post('tags', 'TagsController@store');
Route::get('tags/{tags}', 'TagsController@show');
Route::post('tags/{tags}','TagsController@update');
Route::delete('tags/{tags}', 'TagsController@destroy');


Route::get('{user}/articles', 'UserController@showPosts');
Route::get('{user}/profile', 'UserController@showProfile');
Route::get('{user}/unpublished', 'UserController@unpublished');
Route::get('{user}/favorites', 'UserController@favorites');
Route::get('{user}/changepassword', 'UserController@changePassword');
Route::post('{user}/updatepassword', 'UserController@updatePassword');
Route::delete('{user}/delete', 'UserController@delete');
Route::get('{user}/avatar', 'UserController@avatar');
Route::post('{user}/updateavatar','UserController@updateAvatar');

Route::resource('comment', 'CommentsController');

Route::post('search', 'SearchController@search');

Route::get('tags', 'AdminController@showTags');
Route::get('users','AdminController@showUsers');
Route::get('unpublished', 'AdminController@showUnpublished');

Route::post('upload', 'CropController@upload');
Route::post('crop', 'CropController@crop');

});

