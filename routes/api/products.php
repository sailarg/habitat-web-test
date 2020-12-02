<?php

use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'products', 'as' => 'products.'], function () {

    Route::group(['middleware' => ['parameters']], function () {
        Route::get('{id}', [ProductsController::class, 'getDetail'])->name('getDetail');
        Route::post('{id}/buy', [ProductsController::class, 'buy'])->name('buy');
    });

    Route::get('{show_products_without_stock?}', [ProductsController::class, 'getList'])->name('getList');
});

?>

