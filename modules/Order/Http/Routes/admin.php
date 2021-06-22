<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['admin'])->prefix('admin')->group(function(){
    Route::middleware('can:order')->group(function(){
        Route::prefix("order")->group(function(){
            Route::get("/", "OrderController@index")->name("get.order.list");

            Route::get('/abort-order/{id}', 'OrderController@abort')
                 ->name('get.order.abort_order');
            Route::post('/purchase-order/{id}', 'OrderController@purchase')
                 ->name('post.order.purchase_order');

            Route::get('/order-detail/{id}', 'OrderController@orderDetail')
                 ->name('get.order.order_detail');

            /** Add To Cart */
            Route::get('/add-to-cart/{order_type}/{id}', 'OrderController@getAddToCart')
                 ->name('get.order.add_to_cart');
            Route::post('/add-service-to-cart', 'OrderController@postAddOrderService')
                 ->name('post.order.add_to_cart_service');
            Route::post('/add-course-to-cart', 'OrderController@postAddOrderCourse')
                 ->name('post.order.add_to_cart_course');

            /** Delete from Cart */
            Route::get('/delete-from-cart/{id}', 'OrderController@deleteItem')
                 ->name('get.order.delete_from_cart');
        });
    });
});
