<?php
use App\Http\Controllers\Settings\ServerProvidersController;
use App\Http\Controllers\Auth\UserController;

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

Route::middleware('auth:api')->group(function () {
    Route::post('settings/server-providers', [
        ServerProvidersController::class,
        'store'
    ]);

    Route::get('me', [UserController::class, 'show']);
});

Auth::routes();
