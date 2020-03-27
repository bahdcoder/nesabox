<?php

// Route::on('/')->render('');

use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\App\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Servers\AwsController;
use App\Http\Controllers\Sites\SitesController;
use App\Http\Controllers\Sites\GhostController;
use App\Http\Controllers\Servers\DaemonController;
use App\Http\Controllers\Servers\SshKeysController;
use App\Http\Controllers\Servers\CronJobController;
use App\Http\Controllers\Sites\DeploymentController;
use App\Http\Controllers\Servers\GetServerController;
use App\Http\Controllers\Servers\DatabasesController;
use App\Http\Controllers\Servers\GetServersController;
use App\Http\Controllers\Sites\GitRepositoryController;
use App\Http\Controllers\Servers\CustomServerController;
use App\Http\Controllers\Servers\DigitalOceanController;
use App\Http\Controllers\Servers\CreateServersController;
use App\Http\Controllers\Servers\RegionAndSizeController;
use App\Http\Controllers\Settings\ServerProvidersController;
use App\Http\Controllers\Settings\SourceControlProvidersController;
use App\Http\Controllers\Auth\SshkeysController as UserSshkeysController;
use App\Http\Controllers\NginxController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Pm2Controller;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Servers\DatabaseUserController;
use App\Http\Controllers\Servers\DeleteServerController;
use App\Http\Controllers\Sites\EnvController;
use App\Http\Controllers\Servers\InitializationCallbackController;
use App\Http\Controllers\Servers\MongodbController;
use App\Http\Controllers\Servers\UfwController;
use App\Http\Controllers\Sites\SslCertificateController;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

Route::get('/', [WelcomeController::class, 'index']);

Auth::routes([
    'register' => true,
    'reset' => true,
    'verify' => true
]);

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

Route::get('settings/source-control', [
    SourceControlProvidersController::class,
    'getRedirectUrls'
]);

Route::post('auth/{provider}/callback', [
    SocialiteController::class,
    'handleProviderCallback'
]);

Route::get(
    '/invites/{teamInvite}',
    '\App\Http\Controllers\Users\TeamInvitesController@show'
);

Route::middleware(['auth'])->group(function () {
    Route::get('settings/source-control/{provider}/callback', [
        SourceControlProvidersController::class,
        'handleProviderCallback'
    ]);
});

Route::middleware(['auth'])
    ->prefix('api')
    ->group(function () {
        Route::patch(
            'subscription/update',
            '\App\Http\Controllers\Users\SubscriptionController@update'
        );

        Route::delete(
            'subscription/cancel',
            '\App\Http\Controllers\Users\SubscriptionController@destroy'
        );

        Route::post('settings/server-providers', [
            ServerProvidersController::class,
            'store'
        ]);

        Route::delete('servers/{server}', [
            DeleteServerController::class,
            'destroy'
        ]);

        Route::post('servers/{server}/databases/{database}/mongodb/add-users', [
            MongodbController::class,
            'users'
        ]);

        Route::delete(
            'servers/{server}/databases/{database}/mongodb/delete-databases',
            [MongodbController::class, 'deleteDatabases']
        );

        Route::delete(
            'servers/{server}/databases/{database}/mongodb/delete-users/{databaseUser}',
            [MongodbController::class, 'deleteUsers']
        );

        Route::post('servers/{server}/databases/mongodb/add', [
            MongodbController::class,
            'databases'
        ]);

        Route::get('notifications', [NotificationsController::class, 'index']);

        Route::post('notifications/{notification}', [
            NotificationsController::class,
            'markAsRead'
        ]);

        Route::get('entities/search', [SearchController::class, 'index']);

        Route::delete('settings/server-providers/{credentialId}', [
            ServerProvidersController::class,
            'destroy'
        ]);

        Route::get('servers', [GetServersController::class, 'index']);
        Route::get('servers/own', [GetServersController::class, 'ownServers']);

        Route::get('me', [UserController::class, 'show']);
        Route::put('me', [UserController::class, 'update']);
        Route::post('me/apitoken', [UserController::class, 'apiToken']);
        Route::post('me/sshkeys', [UserSshkeysController::class, 'store']);
        Route::put('me/password', [UserController::class, 'changePassword']);
        Route::delete('me/sshkeys/{sshkey}', [
            UserSshkeysController::class,
            'destroy'
        ]);

        Route::get('servers/regions', [
            RegionAndSizeController::class,
            'index'
        ]);

        Route::get('aws/vpc', [AwsController::class, 'vpc']);
        Route::get('digital-ocean/sizes', [
            DigitalOceanController::class,
            'sizes'
        ]);

        Route::post('settings/source-control/{provider}/unlink', [
            SourceControlProvidersController::class,
            'unlinkProvider'
        ]);

        Route::post('servers', [
            CreateServersController::class,
            'store'
        ])->middleware(['check-create-more-servers']);

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

        Route::get('servers/{server}/databases/{databaseType}', [
            DatabasesController::class,
            'index'
        ]);

        Route::post('servers/{server}/database-users', [
            DatabaseUserController::class,
            'store'
        ]);

        Route::delete('servers/{server}/database-users/{databaseUser}', [
            DatabaseUserController::class,
            'destroy'
        ]);

        Route::delete('servers/{server}/databases/{database}', [
            DatabasesController::class,
            'destroy'
        ]);

        Route::post('servers/{server}/sites', [
            SitesController::class,
            'store'
        ]);

        Route::get('servers/{server}/sites/{site}', [
            SitesController::class,
            'show'
        ]);

        Route::put('servers/{server}/sites/{site}', [
            SitesController::class,
            'update'
        ]);

        Route::delete('servers/{server}/sites/{site}', [
            SitesController::class,
            'destroy'
        ]);

        Route::post('servers/{server}/daemons', [
            DaemonController::class,
            'store'
        ]);

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

        Route::get('servers/{server}/sites/{site}/ghost-config', [
            GhostController::class,
            'getConfig'
        ]);

        Route::post('servers/{server}/sites/{site}/ghost-config', [
            GhostController::class,
            'setConfig'
        ]);

        Route::post('servers/{server}/sites/{site}/install-repository', [
            GitRepositoryController::class,
            'store'
        ]);

        Route::get('servers/{server}/sites/{site}/env-variables', [
            EnvController::class,
            'index'
        ]);

        Route::post('servers/{server}/sites/{site}/env-variables', [
            EnvController::class,
            'store'
        ]);

        Route::delete('servers/{server}/sites/{site}/env-variables/{key}', [
            EnvController::class,
            'destroy'
        ]);

        Route::post('servers/{server}/sites/{site}/deployments', [
            DeploymentController::class,
            'deploy'
        ]);

        Route::get('servers/{server}/sites/{site}/ecosystem-file', [
            Pm2Controller::class,
            'show'
        ]);

        Route::post('servers/{server}/sites/{site}/ecosystem-file', [
            Pm2Controller::class,
            'update'
        ]);

        Route::get('servers/{server}/sites/{site}/nginx-config', [
            NginxController::class,
            'show'
        ]);

        Route::post('servers/{server}/sites/{site}/nginx-config', [
            NginxController::class,
            'update'
        ]);

        Route::post('servers/{server}/firewall-rules', [
            UfwController::class,
            'store'
        ]);

        Route::delete('servers/{server}/firewall-rules/{firewallRule}', [
            UfwController::class,
            'destroy'
        ]);

        Route::post('servers/{server}/sites/{site}/lets-encrypt', [
            SslCertificateController::class,
            'letsEncrypt'
        ]);

        Route::post(
            'servers/{server}/sites/{site}/push-to-deploy',
            '\App\Http\Controllers\Sites\PushToDeployController'
        );

        Route::patch(
            'servers/{server}/sites/{site}/upstream',
            '\App\Http\Controllers\Sites\UpdateBalancedServersController'
        );

        Route::patch(
            'servers/{server}/network',
            '\App\Http\Controllers\Network\UpdateServerNetworkController'
        );

        Route::get(
            'teams/memberships',
            '\App\Http\Controllers\Users\TeamController@memberships'
        );

        Route::get(
            'invites',
            '\App\Http\Controllers\Users\TeamInvitesController@index'
        );

        Route::middleware(['subscribed:business'])->group(function () {
            Route::resource(
                'teams',
                '\App\Http\Controllers\Users\TeamController'
            );

            Route::post(
                'teams/{team}/invites',
                '\App\Http\Controllers\Users\TeamInvitesController@store'
            );

            Route::patch(
                'teams/{team}/servers',
                '\App\Http\Controllers\Users\TeamServersController@update'
            );

            Route::get(
                'teams/{team}/servers',
                '\App\Http\Controllers\Users\TeamServersController@index'
            );
        });

        Route::patch(
            '/invites/{teamInvite}/{status}',
            '\App\Http\Controllers\Users\TeamInvitesController@update'
        );

        Route::delete(
            '/invites/{teamInvite}',
            '\App\Http\Controllers\Users\TeamInvitesController@destroy'
        );
    });

Route::get('get-update-nginx-config/{hash}', [
    NginxController::class,
    'getUpdatingConfig'
]);

Route::middleware(['auth:api'])->group(function () {
    Route::get('servers/{server}/vps', [
        CustomServerController::class,
        'vps'
    ])->name('servers.custom-deploy-script');

    Route::get('sites/{site}/ssl/base-conf', [
        '\App\Http\Controllers\Ssl\FileController',
        'baseConf'
    ]);

    Route::get('sites/{site}/trigger-deployment', [
        DeploymentController::class,
        'http'
    ]);

    Route::post('sites/{site}/trigger-deployment', [
        DeploymentController::class,
        'http'
    ])->name('sites.trigger-deployment');

    Route::post('servers/{server}/initialization-callback', [
        InitializationCallbackController::class,
        'callback'
    ])->name('servers.initialization-callback');

    Route::post(
        'sites/{site}/github-webhooks',
        '\App\Http\Controllers\Sites\GithubWebhookController'
    )->name('github-webhooks');
});

Route::get('/{any}', function ($request) {
    return view('app')->with([
        'auth' => auth()->user()
            ? json_encode((new UserResource(auth()->user()))->toArray($request))
            : null
    ]);
})->where('any', '.*');
