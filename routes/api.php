<?php

use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('login', [\App\Http\Controllers\Api\AuthenticationController::class, 'login']);
    Route::post('register', [\App\Http\Controllers\Api\AuthenticationController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::resource('medication', \App\Http\Controllers\Api\MedicationController::class)
            ->except([
                'create',
                'edit'
            ]);
        Route::resource('medication-reminder', \App\Http\Controllers\Api\MedicationReminderController::class)
            ->except([
                'create',
                'edit'
            ]);
        Route::controller(ProfileController::class)
            ->name('profile.')
            ->group(function () {
                Route::put('profile/', 'update')->name('update');
                Route::put('profile/password', 'update_password')->name('update_password');
            });
    });
});
