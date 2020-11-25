<?php
Route::get('clear_cache', function () {
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('view:clear');
    dd("Cache is cleared");
});

Route::get('home', 'FrontController@home')->name('front.home');
Route::get('/', function () { return redirect()->route('front.home'); });

Route::get('login', 'FrontController@loginPage')->name('front.login-page');
Route::post('login-post', 'FrontController@loginPagePost')->name('front.login-page.post');

Route::post('login', 'FrontController@login')->name('front.login');
Route::post('register', 'FrontController@register')->name('front.register');
Route::post('home-book', 'FrontController@homeBook')->name('front.home-book');

Route::get('forgot-password', 'FrontController@forgotPassword')->name('front.forgot-password');
Route::post('forgot-password-post', 'FrontController@forgotPasswordPost')->name('front.forgot-password.post');

Route::middleware(['customer'])->group(function () {
    Route::get('book', 'FrontController@book')->name('front.book');
    Route::get('book-edit/{id}', 'FrontController@bookEdit')->name('front.book-edit');

    Route::post('book-by-route', 'FrontController@bookByRoute')->name('front.book-by-route');
    Route::post('book-per-hour', 'FrontController@bookPerHour')->name('front.book-per-hour');

    Route::post('logout', 'FrontController@logout')->name('front.logout');
    Route::get('pending', 'FrontController@pending')->name('front.pending');
    Route::get('upcoming', 'FrontController@upcoming')->name('front.upcoming');
    Route::get('past', 'FrontController@past')->name('front.past');
    Route::get('faqs', 'FrontController@faqs')->name('front.faqs');
    Route::get('setting', 'FrontController@setting')->name('front.setting');
    Route::get('reward', 'FrontController@reward')->name('front.reward');
});

Route::prefix('admin')->group(function() {
    Route::get('/', function () { return redirect()->route('home'); });
    Auth::routes();
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('home', 'HomeController@index')->name('home');

    // category route start
    Route::resource('category', 'CategoryController');
    Route::post('category/status/{id?}', 'CategoryController@status')->name('category.status');
    Route::post('category/toggle-status/{id?}/{status?}', 'CategoryController@toggleStatus')->name('category.toggle-status');
    Route::post('category/toggle-all-status/{status?}', 'CategoryController@toggleAllStatus')->name('category.toggle-all-status');
    Route::post('category/service', 'CategoryController@service')->name('category.service');
    // category route end

    // customer route start
    Route::resource('customer', 'CustomerController');
    Route::post('customer/status/{id?}', 'CustomerController@status')->name('customer.status');
    Route::post('customer/toggle-status/{id?}/{status?}', 'CustomerController@toggleStatus')->name('customer.toggle-status');
    Route::post('customer/toggle-all-status/{status?}', 'CustomerController@toggleAllStatus')->name('customer.toggle-all-status');
    Route::post('customer/service', 'CustomerController@service')->name('customer.service');
    // customer route end

    // order route start
    Route::resource('order', 'OrderController');
    Route::post('order/status/{id?}', 'OrderController@status')->name('order.status');
    Route::post('order/toggle-status/{id?}/{status?}', 'OrderController@toggleStatus')->name('order.toggle-status');
    Route::post('order/toggle-all-status/{status?}', 'OrderController@toggleAllStatus')->name('order.toggle-all-status');
    Route::get('order/stream/pdf', 'OrderController@orderPdf')->name('order.pdf');
    // order route end
});