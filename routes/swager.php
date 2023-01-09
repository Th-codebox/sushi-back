<?php


use Illuminate\Support\Facades\Route;


Route::get('web-api', [\App\Http\Controllers\Swager\SwagerController::class, 'web'])->name('web-api');
Route::get('crm-api', [\App\Http\Controllers\Swager\SwagerController::class, 'crm'])->name('crm-api');
Route::get('courier-api', [\App\Http\Controllers\Swager\SwagerController::class, 'courier'])->name('courier-api');
Route::get('tablet-api', [\App\Http\Controllers\Swager\SwagerController::class, 'tablet'])->name('tablet-api');

