<?php



Route::get('clear_cache', function () {
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('view:clear');
    dd("Cache is cleared");
});

Route::get('home', 'FrontController@home')->name('front.home');
Route::get('book', 'FrontController@book')->name('front.book');
Route::post('book-by-route', 'FrontController@bookByRoute')->name('front.book-by-route');
Route::post('book-per-hour', 'FrontController@bookPerHour')->name('front.book-per-hour');
Route::get('pending', 'FrontController@pending')->name('front.pending');
Route::get('upcoming', 'FrontController@upcoming')->name('front.upcoming');
Route::get('past', 'FrontController@past')->name('front.past');
Route::get('faqs', 'FrontController@faqs')->name('front.faqs');
Route::get('setting', 'FrontController@setting')->name('front.setting');
Route::get('reward', 'FrontController@reward')->name('front.reward');

Route::get('login', 'FrontController@loginPage')->name('front.login-page');
Route::post('login-post', 'FrontController@loginPagePost')->name('front.login-page.post');

Route::post('login', 'FrontController@login')->name('front.login');
Route::post('register', 'FrontController@register')->name('front.register');
Route::post('logout', 'FrontController@logout')->name('front.logout');
Route::get('test', 'FrontController@test')->name('front.test');

Route::get('/', function () {
    return redirect()->route('front.home');
});

Route::prefix('admin')->group(function() {
    Route::get('/', function () {
        return redirect()->route('home');
    });
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

    // order route start
    Route::resource('order', 'OrderController');
    Route::post('order/status/{id?}', 'OrderController@status')->name('order.status');
    Route::post('order/toggle-status/{id?}/{status?}', 'OrderController@toggleStatus')->name('order.toggle-status');
    Route::post('order/toggle-all-status/{status?}', 'OrderController@toggleAllStatus')->name('order.toggle-all-status');
    Route::get('order/stream/pdf', 'OrderController@orderPdf')->name('order.pdf');
    // order route end
});