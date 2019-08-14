<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Server;
use App\Scripts\Base;

class DeployGitSite extends Base
{
    /**
     * The server.
     *
     * @var \App\Server
     */
    public $server;

    /**
     * Site to install ghost on
     *
     * @var \App\Site
     */
    public $site;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server, Site $site)
    {
        $this->site = $site;
        $this->server = $server;
    }

    /**
     * Generate the init script
     *
     * @return string
     */
    public function generate()
    {
        $user = SSH_USER;

        $env = $this->site->environment;

        $nodeVersion = isset($env['NODE_VERSION_CONFIG'])
            ? $env['NODE_VERSION_CONFIG']
            : config('nesa.default_node_version');

        $pckManager = isset($env['PCK_MANAGER_CONFIG'])
            ? $env['PCK_MANAGER_CONFIG']
            : config('nesa.default_package_manager');

        // If the user set garbage, just use npm
        if (! in_array($pckManager, ['yarn', 'npm'])) {
            $pckManager = 'npm';
        }

        $startCommand = isset($env['NPM_START_COMMAND_CONFIG'])
            ? $env['NPM_START_COMMAND_CONFIG']
            : config('nesa.default_start_command');
        // Deploy steps

        // 1 - We'll get the site node version from the NODE_VERSION_CONFIG environment variable
        // We'll install it, or make sure its installed:
        // n which {$version}
        // 2 - We'll get the user's preferred package manager from the PCK_MANAGER_CONFIG environment variable, npm or yarn
        // If user selects yarn, then we make sure yarn is installed
        // 3 - We'll make sure pm2 is installed, or rather updated
        // npm i -g pm2
        // 4 - Change directory into the site directory and run a `git pull origin ${repository_branch}`
        // 5 - Run the before deploy script defined by the user
        // 6 - Set all environment variables
        // 7 - Start or restart application process with pm2 using specified user's node version
        // 8 - Run the after deploy script defined by the user

        return <<<EOD
echo "Detected node version {$nodeVersion}";

echo "Detected package manager {$pckManager}";

echo "Installing node version";
n {$nodeVersion}

echo "Updating pm2"
npm install -g pm2

echo "Installing yarn"
npm i -g yarn

echo "Changing directory to site directory"

cd /home/{$user}/{$this->site->name}

echo "Setting environment variables."
{$this->setEnvironmentVariables()}

echo "Running before deploy script"
{$this->site->before_deploy_script}

cd /home/{$user}/{$this->site->name}

echo "Starting application"

pm2 reload {$this->site->name} --update-env || pm2 start {$pckManager} --log ~/.pm2/logs/{$this->site->name} --no-automation --name {$this->site->name} --interpreter /usr/local/n/versions/node/{$nodeVersion}/bin/node -- run {$startCommand}
{$this->generateWorkerProcessses($nodeVersion, $pckManager)}
echo "Running after deploy script"

{$this->site->after_deploy_script}
EOD;
    }

    public function generateWorkerProcessses($nodeVersion, $pckManager)
    {
        $script = '';

        foreach($this->site->pm2ProcessesExceptWeb() as $process):
            $script.= <<<EOD
\n
echo "Starting process : {$process->name}";
pm2 reload {$process->slug} --update-env || pm2 start {$pckManager} --log ~/.pm2/logs/{$process->slug} --no-automation --name {$process->slug} --interpreter /usr/local/n/versions/node/{$nodeVersion}/bin/node -- run {$process->command}
EOD;
        endforeach;

        return $script;
    }

    public function setEnvironmentVariables()
    {
        $env = $this->site->environment;

        $script = '';

        foreach ($env as $key => $value):
            $script .= <<<EOD
\n
export "{$key}"="{$value}"
EOD;
        endforeach;

        return $script;
    }
}
