<?php

use Illuminate\Support\Facades\Route;
use WebBestPractice\Cookies\Http\Controllers\ConsentController;

Route::post('accept', [ConsentController::class, 'accept'])->name('accept');
Route::post('reject', [ConsentController::class, 'reject'])->name('reject');
