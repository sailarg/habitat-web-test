<?php

use App\Http\Controllers\BuyersController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'buyers', 'as' => 'buyers.'], function () {

    Route::get('', [BuyersController::class, 'getList'])->name('getList');
    Route::get('{id}', [BuyersController::class, 'getDetail'])->name('getDetail')
        ->middleware('parameters');
});

?>

