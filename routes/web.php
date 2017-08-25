<?php

Route::get('/', 'PageController@index')->name('home');

Auth::routes();

/* profile */
Route::get('/profile/{user}', 'PageController@profile');

/* product page */
Route::get('/products', 'PageController@list')->name('products');

/* AJAX search requests */
Route::post('/cat/{cat?}/search/{order?}/{search?}', 'PageController@search');
Route::post('/search/{order}/{search?}', 'PageController@search');
Route::post('/cat/{order?}/{cat?}', 'PageController@search');

/* Resources */
Route::resource('/product', 'ProductController');
Route::resource('/product/category', 'CategoryController');

/* checkout */
Route::get('/checkout', 'PaymentController@index');
Route::post('/charge', 'PaymentController@chargeCard');