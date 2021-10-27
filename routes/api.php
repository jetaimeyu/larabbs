<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->namespace('Api')->name('api.v1.')->group(function (){
    Route::middleware('throttle:'.config('api.rate_limits.sign'))->group(function (){
        Route::post('verificationCodes', [\App\Http\Controllers\Api\VerificationCodesController::class,'store'])->name('verificationCodes.store');
        Route::post('users', [\App\Http\Controllers\Api\UsersController::class, 'store'])->name('users.store');
        //图片验证码
        Route::post('captchas', [\App\Http\Controllers\Api\CaptchasController::class, 'store'])->name('captchas.store');
    });
    Route::middleware('throttle:'.config('api.rate_limits.access'))->group(function (){

    });

});

