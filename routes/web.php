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

Route::get('/', 'RoutingController@route');
Route::get('test', 'RoutingController@test');


// Admin Interface Routes
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function()
{
	Route::get('dashboard', 'Admin\AdminController@dashboard');
    CRUD::resource('route', 'Admin\RouteCrudController');
    CRUD::resource('visit', 'Admin\VisitCrudController');
  
  // [...] other routes
});