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

Auth::routes();
Route::get('home', 'HomeController@index')->name('home');
Route::get('nearest-events', 'Web\EventsController@nearest')->name('web.events.nearest');

Route::group([
    'prefix' => 'panel',
    'middleware' => 'auth',
    'as' => 'panel.'
], function () {

    Route::get('events', 'Panel\EventsController@index')->name('events.index');
    Route::group([
        'prefix' => 'ajax'
    ], function () {
        Route::resource('events', 'API\EventsController')->only([
            'store', 'update', 'delete'
        ]);
    });
});
