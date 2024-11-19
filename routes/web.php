<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('order/create');
});


Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{order}/preview', [OrderController::class, 'preview'])->name('order.preview');
Route::get('/order/{order}/pdf', [OrderController::class, 'generatePDF'])->name('order.generatePDF');

Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{order}/preview', [OrderController::class, 'preview'])->name('order.preview');
Route::get('/order/{order}/print', [OrderController::class, 'generatePDF'])->name('order.print');
