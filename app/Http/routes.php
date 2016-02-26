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

Route::get('/', function () {
    return view('welcome');
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
	

Route::resource('articles', 'ArticlesController');
Route::get('unpublished', 'ArticlesController@unpublished');
Route::delete('articles/{article}/delete', 'ArticlesController@delete');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
	]);
	

Route::get('tags/{tags}', 'TagsController@show');

Route::get('users','UserController@index');
Route::get('{user}/articles', 'UserController@showPosts');
Route::get('{user}/profile', 'UserController@showProfile');
Route::get('{user}/unpublished', 'UserController@unpublished');
Route::delete('{user}/delete', 'UserController@delete');

Route::post('comment/add','CommentsController@store');
Route::delete('comment/delete/{comment}','CommentsController@delete');

});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
});
