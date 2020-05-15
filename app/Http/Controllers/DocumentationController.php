<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function index()
    {
        $menu = collect([
            [
                'name' => 'GENERAL',
                'items' => collect([
                    [
                        'name' => 'Getting started',
                        'path' => 'general/welcome',
                    ],
                ])
            ],
            [
                'name' => 'Account',
                'items' => collect([
                    [
                        'name' => 'Server providers',
                        'path' => 'account/server-providers'
                    ],
                    [
                        'name' => 'Source control providers',
                        'path' => 'account/source-control-providers'
                    ],
                ])
                ],
            [
                'name' => 'Server management',
                'items' => collect([
                    [
                        'name' => 'Databases',
                        'path' => 'server-management/databases'
                    ],
                    [
                        'name' => 'Cron jobs',
                        'path' => 'server-management/cron-jobs'
                    ],
                    [
                        'name' => 'Daemons',
                        'path' => 'server-management/daemons'
                    ],
                    [
                        'name' => 'Firewall',
                        'path' => 'server-management/firewall'
                    ],
                    [
                        'name' => 'Ssh keys',
                        'path' => 'server-management/ssh-keys'
                    ]
                ])
            ]
        ]);

        return view('app.docs', compact('menu'));
    }
}
