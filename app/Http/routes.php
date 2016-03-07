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

Route::resource('articles', 'ArticlesController');

Route::get('tags/{tags}', 'TagsController@show');
Route::delete('tags/{tags}', 'TagsController@destroy');


Route::get('{user}/articles', 'UserController@showPosts');
Route::get('{user}/profile', 'UserController@showProfile');
Route::get('{user}/unpublished', 'UserController@unpublished');
Route::delete('{user}/delete', 'UserController@delete');

Route::resource('comment', 'CommentsController');

Route::post('search', 'SearchController@search');

Route::get('tags', 'AdminController@showTags');
Route::get('users','AdminController@showUsers');
Route::get('unpublished', 'AdminController@showUnpublished');



});

