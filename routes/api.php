<?php
Route::namespace('Api')->group(function() {
    Route::post('login', 'ApiController@login');
    Route::post('register', 'ApiController@register');
    Route::post('otp-verify', 'ApiController@otpVerify');
});

Route::namespace('Api')->middleware('jwt.auth')->group(function() {
    Route::post('logout', 'ApiController@logout');
    Route::post('me', 'ApiController@me');

    Route::post('common', 'ApiController@common');
    Route::post('home', 'ApiController@home');
    Route::post('product', 'ApiController@product');
    Route::post('product-detail', 'ApiController@productDetail');
    Route::post('search', 'ApiController@search');
    
    // cart route start
    Route::post('add-to-cart', 'ApiController@addToCart');
    Route::post('update-cart', 'ApiController@updateCart');
    Route::post('delete-from-cart', 'ApiController@deleteFromCart');
    Route::post('cart-detail', 'ApiController@cartDetail');
    // cart route end
    
    // address route start
    Route::post('add-address', 'ApiController@addAddress');
    Route::post('edit-address', 'ApiController@editAddress');
    Route::post('list-address', 'ApiController@listAddress');
    Route::post('delete-address', 'ApiController@deleteAddress');
    // address route end
    
    // order route start
    Route::post('place-order', 'ApiController@placeOrder');
    Route::post('list-order', 'ApiController@listOrder');
    Route::post('order-detail', 'ApiController@orderDetail');
    Route::post('apply-coupon', 'ApiController@applyCoupon');
    Route::post('cancel-order', 'ApiController@cancelOrder');
    // order route end
    
    // user route start
    Route::post('profile', 'ApiController@profile');
    // user route end

    // route start
    Route::post('notification', 'ApiController@notification');
    // route end
});