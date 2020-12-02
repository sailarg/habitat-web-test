<?php

use App\Http\Controllers\SellersController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sellers', 'as' => 'sellers.'], function () {

    Route::get('', [SellersController::class, 'getList'])->name('getList');
    Route::get('{id}', [SellersController::class, 'getDetail'])->name('getDetail')
        ->middleware('parameters');

    Route::post('/product', [SellersController::class, 'addProduct'])->name('addProduct');
});

?>

