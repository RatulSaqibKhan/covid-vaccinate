<?php

use App\Http\Controllers\API\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('register', [UserController::class, 'register']);
    Route::get('search/{nid}', [UserController::class, 'search']);
});
