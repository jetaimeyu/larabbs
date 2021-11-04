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

Route::prefix('v1')->namespace('Api')->name('api.v1.')->group(function () {
    Route::middleware('throttle:' . config('api.rate_limits.sign'))->group(function () {
        //图片验证码
        Route::post('captchas', [\App\Http\Controllers\Api\CaptchasController::class, 'store'])->name('captchas.store');
        //手机验证码
        Route::post('verificationCodes', [\App\Http\Controllers\Api\VerificationCodesController::class, 'store'])->name('verificationCodes.store');
        // 用户注册
        Route::post('users', [\App\Http\Controllers\Api\UsersController::class, 'store'])->name('users.store');
        // 第三方登录
        Route::post('socials/{social_type}/authorizations', [\App\Http\Controllers\Api\AuthorizationController::class, 'socialStore'])->where('social_type', 'wechat')->name('social.authorizations.store');
        //登录
        Route::post('authorizations', 'AuthorizationController@store')->name('authorizations.store');
    });
    Route::middleware('throttle:' . config('api.rate_limits.access'))->group(function () {


        Route::get('users/{user}', 'UsersController@show')->name('users.show');
        Route::get('categories', 'CategoriesController@index')->name('categories.index');
        //某个用户发布的话题列表
        Route::get('users/{user}/topics', [\App\Http\Controllers\Api\TopicsController::class, 'userIndex'])->name('users.topics.index');
        Route::resource('topics', 'TopicsController')->only([
            'index', 'show'
        ]);
        //话题回复列表
        Route::get('topics/{topic}/replies', 'RepliesController@index')->name('topics.replies.index');
        //某个用户的回复列表
        Route::get('users/{user}/replies', 'RepliesController@userIndex')->name('users.replies.index');
        Route::get('links', 'LinksController@index')->name('links.index');
        // 登陆后可访问的接口
        Route::middleware('auth:api')->group(function (){
            Route::get('user', 'UsersController@me')->name('user.show');
            //图片上传接口
            Route::post('images', 'ImagesController@store')->name('images.store');
            //编剧用户信息
            Route::patch('user', 'UsersController@update')->name('user.update');
            Route::resource('topics','TopicsController')->only(['store', 'update', 'destroy']);
            //发布回复
            Route::post('topics/{topic}/replies', 'RepliesController@store')->name('topics.replies.store');
            //删除回复
            Route::delete('topics/{topic}/replies/{reply}','RepliesController@destroy')->name('topic.replies.destroy');

            //通知列表
            Route::get('notifications', 'NotificationsController@index')->name('notifications.index');

            //通知统计
            Route::get('notifications/stats', 'NotificationsController@stats')->name('notifications.stats');

            // 标记通知为已读
            Route::patch('user/read/notifications', 'NotificationsController@read')->name('user.notifications.read');
            // 单条通知标记为已读
            Route::patch('user/read/notifications/{notification}', 'NotificationsController@readOne')->name('user.notifications.readOne');
            // 当前登录用户权限
            Route::get('user/permissions', 'PermissionsController@index')->name('user.permissions.index');
        });


    });

});

