<?php
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
use App\Http\Controllers\FileController;
use App\Http\Controllers\NginxController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Pm2Controller;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Sites\EnvController;
use App\Http\Controllers\Servers\InitializationCallbackController;
use App\Http\Controllers\Servers\MongodbController;
use App\Http\Controllers\Servers\MonitoringController;
use App\Http\Controllers\Servers\UfwController;
use App\Http\Controllers\Sites\GithubWebhookController;
use App\Server;
use App\Http\Controllers\Sites\Pm2ProcessController;
use App\Http\Controllers\Sites\SslCertificateController;

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

    Route::get('servers/{server}/databases/{databaseType}', [
        DatabasesController::class,
        'index'
    ]);

    Route::delete('servers/{server}/databases/{database}', [
        DatabasesController::class,
        'destroy'
    ]);

    Route::post('servers/{server}/sites', [SitesController::class, 'store']);

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

    Route::post('servers/{server}/install-monitoring', [
        MonitoringController::class,
        'store'
    ]);

    Route::get('servers/{server}/metrics', [
        MonitoringController::class,
        'index'
    ]);

    Route::post('servers/{server}/sites/{site}/pm2-processes', [
        Pm2ProcessController::class,
        'store'
    ]);

    Route::delete('servers/{server}/sites/{site}/pm2-processes/{pm2-process}', [
        Pm2ProcessController::class,
        'destroy'
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
});

Route::get('get-update-nginx-config/{hash}', [
    NginxController::class,
    'getUpdatingConfig'
]);

Route::middleware(['guest', 'api-token'])->group(function () {
    Route::get('servers/{server}/vps', [
        CustomServerController::class,
        'vps'
    ])->name('servers.custom-deploy-script');

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
        'servers/{server}/sites/{site}',
        '\App\Http\Controllers\Sites\PushToDeployController'
    );

    Route::get(
        'sites/{site}/github-webhooks',
        '\App\Http\Controllers\Sites\GithubWebhookController'
    );
});

// $this->post('login', 'Auth\LoginController@login');
// $this->post('register', 'Auth\RegisterController@register');
// $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// $this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Auth::routes();

Route::get('/nesa-metrics/package.json', [
    FileController::class,
    'nesaMetricsPackageJson'
]);

Route::get('/nesa-metrics/index.js', [
    FileController::class,
    'nesaMetricsIndexJs'
]);

Route::get('logs-watcher-package-json', function () {
    return <<<EOD
{
    "name": "watch-files-app",
    "version": "1.0.0",
    "description": "",
    "main": "index.js",
    "scripts": {
        "start": "node index"
    },
    "keywords": [],
    "author": "",
    "license": "ISC",
    "dependencies": {
        "axios": "^0.19.0",
        "read-last-lines": "^1.7.1",
        "socket.io": "^2.2.0",
        "throttle-debounce": "^2.1.0"
    }
}
EOD;
});

Route::get('default-servers-nginx-config', function (Server $server) {
    return <<<EOD
user www-data;
worker_processes auto;
pid /run/nginx.pid;
include /etc/nginx/modules-enabled/*.conf;

events {
        worker_connections 768;
        # multi_accept on;
}

http {

        ##
        # Basic Settings
        ##

        sendfile on;
        tcp_nopush on;
        tcp_nodelay on;
        keepalive_timeout 65;
        types_hash_max_size 2048;
        # server_tokens off;

        server_names_hash_bucket_size 128;
        # server_name_in_redirect off;

        include /etc/nginx/mime.types;
        default_type application/octet-stream;

        ##
        # SSL Settings
        ##

        ssl_protocols TLSv1 TLSv1.1 TLSv1.2; # Dropping SSLv3, ref: POODLE
        ssl_prefer_server_ciphers on;

        ##
        # Logging Settings
        ##

        access_log /var/log/nginx/access.log;
        error_log /var/log/nginx/error.log;

        ##
        # Gzip Settings
        ##

        gzip on;

        # gzip_vary on;
        # gzip_proxied any;
        # gzip_comp_level 6;
        # gzip_buffers 16 8k;
        # gzip_http_version 1.1;
        # gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;

        ##
        # Virtual Host Configs
        ##

        include /etc/nginx/conf.d/*.conf;
        include /etc/nginx/sites-enabled/*;
        # include /etc/nginx/nesabox-sites-enabled/*;
}


#mail {
#       # See sample authentication script at:
#       # http://wiki.nginx.org/ImapAuthenticateWithApachePhpScript
# 
#       # auth_http localhost/auth.php;
#       # pop3_capabilities "TOP" "USER";
#       # imap_capabilities "IMAP4rev1" "UIDPLUS";
# 
#       server {
#               listen     localhost:110;
#               protocol   pop3;
#               proxy      on;
#       }
# 
#       server {
#               listen     localhost:143;
#               protocol   imap;
#               proxy      on;
#       }
#}
EOD;
})->name('default-servers-nginx-config');

Route::get('logs-watcher-nginx-config/{server}', function (Server $server) {
    $name = $server->getLogWatcherSiteDomain();

    return <<<EOD
server {
    listen 80;
    server_name {$name};

    location / {
        proxy_pass http://localhost:23443;
        proxy_set_header Host \$http_host;
        proxy_set_header X-NginX-Proxy true;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_http_version 1.1;
        proxy_set_header Upgrade \$http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_max_temp_file_size 0;
        proxy_redirect off;
        proxy_read_timeout 240s;
        proxy_set_header X-Forwarded-Proto \$scheme;
        proxy_set_header X-Real-IP \$remote_addr;
    }
}
EOD;
});

Route::get('logs-watcher-index-js', function () {
    return <<<EOD
const Fs = require('fs')
const Http = require('http')
const Axios = require('axios')
const SocketIo = require('socket.io')
const ReadLastLines = require('read-last-lines')

const API_URL = process.env.API_URL

const Server = Http.createServer((req, res) => {})

const Io = SocketIo(Server)

Io.on('connection', socket => {
    socket.on(
        'subscribe',
        ({ access_token, linesCount = 1000, filePaths } = {}) => {
            Axios.get(`\${API_URL}/me`, {
                headers: {
                    Authorization: `Bearer \${access_token}`
                }
            }).then(({}) => {
                // We'll emit the logs for each of the watched files, before proceeding to
                // setup watchers
                const emitFileContent = (filePath) =>
                    ReadLastLines.read(filePath, linesCount).then(lines => {
                        socket.emit(`\${filePath}`, lines)
                    })

                const filesToWatch = filePaths.split(' ')

                filesToWatch.forEach(filePath => {
                    if (! Fs.existsSync(filePath)) return

                    emitFileContent(filePath)
                    Fs.watchFile(filePath, () => emitFileContent(filePath))
                })
            })
        }
    )
})

Server.listen(process.env.PORT)

EOD;
});
