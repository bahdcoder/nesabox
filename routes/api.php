<?php
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Servers\AwsController;
use App\Http\Controllers\Sites\SitesController;
use App\Http\Controllers\Servers\SshKeysController;
use App\Http\Controllers\Servers\GetServerController;
use App\Http\Controllers\Servers\DatabasesController;
use App\Http\Controllers\Servers\GetServersController;
use App\Http\Controllers\Servers\CustomServerController;
use App\Http\Controllers\Servers\DigitalOceanController;
use App\Http\Controllers\Servers\CreateServersController;
use App\Http\Controllers\Servers\RegionAndSizeController;
use App\Http\Controllers\Settings\ServerProvidersController;
use App\Http\Controllers\Settings\SourceControlProvidersController;
use App\Http\Controllers\Auth\SshkeysController as UserSshkeysController;
use App\Notifications\Servers\ServerIsReady;
use App\Http\Controllers\Servers\DaemonController;
use App\Http\Controllers\Servers\CronJobController;
use App\Http\Controllers\Sites\GhostController;

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

Route::middleware(['auth:api'])->group(function () {
    Route::post('settings/server-providers', [
        ServerProvidersController::class,
        'store'
    ]);

    Route::delete('settings/server-providers/{credentialId}', [
        ServerProvidersController::class,
        'destroy'
    ]);

    Route::get('servers', [GetServersController::class, 'index']);

    Route::get('me', [UserController::class, 'show']);
    Route::put('me', [UserController::class, 'update']);
    Route::post('me/apitoken', [UserController::class, 'apiToken']);
    Route::post('me/sshkeys', [UserSshkeysController::class, 'store']);
    Route::put('me/password', [UserController::class, 'changePassword']);
    Route::delete('me/sshkeys/{sshkey}', [
        UserSshkeysController::class,
        'destroy'
    ]);

    Route::get('servers/regions', [RegionAndSizeController::class, 'index']);

    Route::get('aws/vpc', [AwsController::class, 'vpc']);
    Route::get('digital-ocean/sizes', [DigitalOceanController::class, 'sizes']);

    Route::get('settings/source-control/{provider}', [
        SourceControlProvidersController::class,
        'getRedirectUrl'
    ]);

    Route::get('settings/source-control/{provider}/callback', [
        SourceControlProvidersController::class,
        'handleProviderCallback'
    ]);

    Route::post('settings/source-control/{provider}/unlink', [
        SourceControlProvidersController::class,
        'unlinkProvider'
    ]);

    Route::post('servers', [CreateServersController::class, 'store']);
    Route::get('servers/{server}', [GetServerController::class, 'show']);

    Route::post('servers/{server}/sshkeys', [
        SshKeysController::class,
        'store'
    ]);

    Route::delete('servers/{server}/sshkeys/{sshkey}', [
        SshKeysController::class,
        'destroy'
    ]);

    Route::post('servers/{server}/databases', [
        DatabasesController::class,
        'store'
    ]);

    Route::delete('servers/{server}/databases/{database}', [
        DatabasesController::class,
        'destroy'
    ]);

    Route::post('servers/{server}/sites', [SitesController::class, 'store']);

    Route::post('servers/{server}/daemons', [DaemonController::class, 'store']);
    Route::delete('servers/{server}/daemons/{daemon}', [
        DaemonController::class,
        'destroy'
    ]);
    Route::get('servers/{server}/daemons/{daemon}/status', [
        DaemonController::class,
        'status'
    ]);
    Route::post('servers/{server}/daemons/{daemon}/restart', [
        DaemonController::class,
        'restart'
    ]);

    Route::post('servers/{server}/cron-jobs', [
        CronJobController::class,
        'store'
    ]);
    Route::post('servers/{server}/cron-jobs/{job}/log', [
        CronJobController::class,
        'log'
    ]);
    Route::delete('servers/{server}/cron-jobs/{job}', [
        CronJobController::class,
        'destroy'
    ]);

    Route::post('servers/{server}/sites/{site}/install-ghost', [
        GhostController::class,
        'store'
    ]);

    Route::post('servers/{server}/sites/{site}/uninstall-ghost', [
        GhostController::class,
        'destroy'
    ]);
});

Route::middleware(['guest', 'api-token'])->group(function () {
    Route::get('servers/{server}/vps', [
        CustomServerController::class,
        'vps'
    ])->name('servers.custom-deploy-script');
});

// $this->post('login', 'Auth\LoginController@login');
// $this->post('register', 'Auth\RegisterController@register');
// $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// $this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Auth::routes();

Route::get('beans', function () {
    \App\User::first()->notify(
        new ServerIsReady(\App\Server::latest()->first())
    );
});
