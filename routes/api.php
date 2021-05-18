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

Route::prefix('v1')
    ->namespace('Api\V1')
    ->name('api.v1.')
    ->group(function() {
        Route::middleware('throttle:' . config('api.rate_limits.sign'))->group(function () {
            Route::post('users', 'UsersController@store');

            Route::get('users', 'UsersController@index');

            // 平台列表
            Route::get('platforms', 'PlatformsController@index');
            // 新增平台
            Route::post('platforms', 'PlatformsController@store');
            // 修改平台
            Route::patch('platforms/{platform}', 'PlatformsController@udpate');
            // 删除平台
            Route::delete('platforms/{platform}', 'PlatformsController@destroy');

            // 权限规则列表
            Route::get('rules', 'RulesController@index');
            // 新增规则
            Route::post('rules', 'RulesController@store');
            // 获取某个规则
            Route::get('rules/{rule}', 'RulesController@show');
            // 修改规则
            Route::patch('rules/{rule}', 'RulesController@update');
        });

        Route::middleware('throttle:' . config('api.rate_limits.access'))->group(function () {

            // 登录之后可以访问的接口
            Route::middleware('auth:api')->group(function () {

            });
        });
});
