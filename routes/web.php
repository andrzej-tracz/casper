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

Route::get('/', 'Web\EventsController@upcomingEvents')->name('web.index');
Route::get('nearest-events', 'Web\EventsController@nearest')->name('web.events.nearest');

Auth::routes();

Route::get('auth/fb/redirect', 'Auth\SocialFacebookController@redirect')->name('auth.fb.redirect');
Route::get('auth/fb/callback', 'Auth\SocialFacebookController@callback')->name('auth.fb.callback');

Route::group([
    'prefix' => 'event',
    'as' => 'web.event.'
], function () {
    Route::get('{event}', 'Web\EventsController@details')->name('details')->middleware('can:view,event');
    Route::post('join/{event}', 'Web\EventsController@join')->name('join')->middleware('can:join,event');

    Route::group([
        'prefix' => 'ajax',
        'as' => 'ajax.',
    ], function () {
        Route::get('nearest-events-search', 'API\EventsController@searchNearest')->name('search');
    });
});

Route::group([
    'middleware' => 'auth'
], function () {

    Route::get('invitation/{token}', 'Web\EventInvitationsController@show')->name('events.invitation.show');
    Route::put('invitation/{invitation}/accept', 'Web\EventInvitationsController@accept')->name('events.invitation.accept');

    Route::group([
        'prefix' => 'panel',
        'as' => 'panel.'
    ], function () {
        Route::get('events', 'Panel\EventsController@index')->name('events.index');

        Route::group([
            'prefix' => 'ajax',
            'as' => 'ajax.',
        ], function () {

            Route::apiResource('events', 'API\EventsController');
            Route::apiResource('guests', 'API\GuestController')
                ->only([
                    'destroy'
                ]);

            Route::apiResource('events.invitations', 'API\EventInvitationsController')
                ->only([
                    'index', 'store', 'destroy'
                ]);
            Route::put('events/invitations/{invitation}/accept', 'API\EventInvitationsController@accept')
                ->name('events.invitations.accept');

            Route::get('users-search', 'API\UserController@search')->name('users.search');
        });
    });
});
