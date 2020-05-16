<?php

namespace App\Http\Controllers;

class DocumentationController extends Controller
{
    public function index()
    {
        $path = request()->path();

        $menu = collect([
            [
                'name' => 'General',
                'items' => collect([
                    [
                        'name' => 'Getting started',
                        'path' => 'general/getting-started',
                        'next' => 'account/server-providers'
                    ]
                ])
            ],
            [
                'name' => 'Account',
                'items' => collect([
                    [
                        'name' => 'Server providers',
                        'path' => 'account/server-providers',
                        'next' => 'account/digital-ocean'
                    ],
                    [
                        'name' => 'Digital ocean',
                        'path' => 'account/digital-ocean',
                        'next' => 'account/linode'
                    ],
                    [
                        'name' => 'Linode provider',
                        'path' => 'account/linode',
                        'next' => 'account/vultr'
                    ],
                    [
                        'name' => 'Vultr provider',
                        'path' => 'account/vultr',
                        'next' => 'account/source-control-providers'
                    ],
                    [
                        'name' => 'Source control providers',
                        'path' => 'account/source-control-providers',
                        'next' => 'account/ssh-keys'
                    ],
                    [
                        'name' => 'Ssh keys',
                        'path' => 'account/ssh-keys',
                        'next' => 'server-management/provision-a-server'
                    ]
                ])
            ],
            [
                'name' => 'Server management',
                'items' => collect([
                    [
                        'name' => 'Provision a server',
                        'path' => 'server-management/provision-a-server',
                        'next' => 'server-management/databases'
                    ],
                    [
                        'name' => 'Databases',
                        'path' => 'server-management/databases',
                        'next' => 'server-management/cron-jobs'
                    ],
                    [
                        'name' => 'Cron jobs',
                        'path' => 'server-management/cron-jobs',
                        'next' => 'server-management/daemons'
                    ],
                    [
                        'name' => 'Daemons',
                        'path' => 'server-management/daemons',
                        'next' => 'server-management/firewall'
                    ],
                    [
                        'name' => 'Firewall',
                        'path' => 'server-management/firewall',
                        'next' => 'server-management/ssh-keys'
                    ],
                    [
                        'name' => 'Ssh keys',
                        'path' => 'server-management/ssh-keys',
                        'next' => 'sites/git-repositories'
                    ]
                ])
            ],
            [
                'name' => 'Sites',
                'items' => collect([
                    [
                        'name' => 'Git repositories',
                        'path' => 'sites/git-repositories',
                        'next' => 'sites/ssl-certificates'
                    ],
                    [
                        'name' => 'SSL certificates',
                        'path' => 'sites/ssl-certificates',
                        'next' => 'sites/nginx-configuration'
                    ],
                    [
                        'name' => 'Nginx Configuration',
                        'path' => 'sites/nginx-configuration',
                        'next' => 'sites/pm2-configuration'
                    ],
                    [
                        'name' => 'Pm2 Configuration',
                        'path' => 'sites/pm2-configuration',
                        'next' => 'sites/deployments'
                    ],
                    [
                        'name' => 'Deployments',
                        'path' => 'sites/deployments',
                        'next' => null
                    ]
                ])
            ]
        ]);

        if ($path === 'docs') {
            return redirect('/docs/' . $menu->first()['items'][0]['path']);
        }

        $page = null;

        $section = $menu->first(function ($section) use ($path) {
            $match = $section['items']->first(function ($current) use ($path) {
                return strpos($path, $current['path']);
            });

            return $match;
        });

        if ($section) {
            $page = $section['items']->first(function ($current) use ($path) {
                return strpos($path, $current['path']);
            });
        }

        if (!$page || !$section) {
            return redirect('/docs/' . $menu->first()['items'][0]['path']);
        }

        $nextPage = $section['items']->first(function ($current) use ($path) {
            $current = strpos($path, $current['path']);
        });

        return view('app.docs', [
            'menu' => $menu,
            'current' => $page,
            'page' => str_replace('/', '.', $page['path'])
        ]);
    }
}
