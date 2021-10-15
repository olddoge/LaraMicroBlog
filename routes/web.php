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

// 主路由
Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');
// 用户注册
Route::get('signup', 'UsersController@create')->name('signup');
// 生成用户注册相关的资源路由
// 资源路由遵从 RESTful 架构，包含（GET，POST，PATCH，DELETE）
Route::resource('users', 'UsersController');
// 登陆登出会话管理
Route::get('login', 'SessionsController@create')->name('login');    // 登陆的页面
Route::post('login', 'SessionsController@store')->name('login');    // 登陆操作的处理
Route::delete('logout', 'SessionsController@destroy')->name('logout');  // 登出