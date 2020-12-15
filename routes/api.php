<?php
Route::namespace('Api')->group(function() {
    Route::post('login', 'ApiController@login');
    Route::post('register', 'ApiController@register');
    Route::post('otp-verify', 'ApiController@otpVerify');
});

// agency code start
Route::prefix('agency')->namespace('Api')->middleware(['jwt.auth', 'jwt.agency'])->group(function() {
    Route::post('logout', 'AgencyController@logout');
    Route::post('common', 'AgencyController@common');
});
// agency code end

// customer code start
Route::prefix('customer')->namespace('Api')->middleware(['jwt.auth', 'jwt.customer'])->group(function() {
    Route::post('logout', 'CustomerController@logout');
    Route::post('common', 'CustomerController@common');
});
// customer code end