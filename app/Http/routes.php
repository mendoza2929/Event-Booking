<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'WelcomeController@index');
Route::get('home', 'HomeController@index');



Route::group(['prefix' => 'api'], function () {

    Route::post('register', 'AuthController@register');
    Route::post('login',    'AuthController@login');
    Route::get('events',    'EventController@index');
    Route::get('events/{id}','EventController@show');

    Route::group(['middleware' => 'api.token'], function () {

    Route::post('logout', 'AuthController@logout');
    Route::get('me', function () { return response()->json(Auth::user()); });

    // Customer routes
    Route::group(['middleware' => 'roleCustomer'], function () {
        Route::post('tickets/{ticket}/bookings', 'Customer\BookingController@store')
        ->middleware('prevent.double.booking');
        Route::get('bookings', 'Customer\BookingController@index');
        Route::put('bookings/{id}/cancel', 'Customer\BookingController@cancel');
        Route::post('bookings/{id}/payment', 'Customer\PaymentController@pay');
        Route::get('payments/{id}', 'Customer\PaymentController@show');
    });

    // Organizer/Admin routes
    Route::group(['middleware' => 'roleOrganizerAdmin'], function () {
        Route::post('events', 'Organizer\EventController@store');
        Route::put('events/{id}', 'Organizer\EventController@update');
        Route::delete('events/{id}', 'Organizer\EventController@destroy');
        Route::post('events/{event}/tickets', 'Organizer\TicketController@store');
        Route::put('tickets/{id}', 'Organizer\TicketController@update');
        Route::delete('tickets/{id}', 'Organizer\TicketController@destroy');
    });

});

   
});