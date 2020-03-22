<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ProcessRunner', function ($app, $params) {
            return new Process($params['command']);
        });

        Inertia::share([
            'auth' => function () {
                return [
                    'user' => Auth::user() ? Auth::user() : null
                ];
            },
            'flash' => function () {
                return [
                    'success' => Session::get('success'),
                    'error' => Session::get('error')
                ];
            },
            'errors' => function () {
                return Session::get('errors')
                    ? Session::get('errors')
                        ->getBag('default')
                        ->getMessages()
                    : (object) [];
            }
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();
    }
}
