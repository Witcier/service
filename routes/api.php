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
        });

        Route::middleware('throttle:' . config('api.rate_limits.access'))->group(function () {

            // 登录之后可以访问的接口
            Route::middleware('auth:api')->group(function () {

            });
        });
});
