<?php

use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\VaccineCenterController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('register', [UserController::class, 'register'])->name('user.register');
    Route::get('search/{nid}', [UserController::class, 'search'])->name('user.search');
    Route::get('vaccine-centers', [VaccineCenterController::class, 'index'])->name('vaccine-center.list');
});
