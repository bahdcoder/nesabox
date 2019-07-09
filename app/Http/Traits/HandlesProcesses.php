<?php

namespace App\Http\Traits;

use App\Site;
use App\Server;
use App\Sshkey;
use App\Scripts\Server\Init;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

trait HandlesProcesses
{
    /**
     * Start and return a new process
     *
     * @return \Symfony\Component\Process\Process
     */
    public function execProcess($command, $mustRun = false, $timeOut = 3600)
    {
        $process = new Process($command);

        $process->setTimeout($timeOut);

        $process->{$mustRun ? 'mustRun' : 'run'}();

        return $process;
    }

    /**
     * Start and return a new process
     *
     * @return \Symfony\Component\Process\Process
     */
    public function execProcessAsync($command, $callback, $timeOut = 3600)
    {
        $process = new Process($command);

        $process->setTimeout($timeOut);

        $process->start();

        foreach ($process as $type => $data):
            $callback($data);
        endforeach;

        return $process;
    }

    /**
     * Run the init script on a server
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runInitServerScript(Server $server)
    {
        $arguments = $this->buildArgumentsFor('init', $server);

        $scriptPath = base_path('scripts/init.sh');

        return $this->execProcess(
            "ssh -o StrictHostKeyChecking=no root@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptPath} {$arguments}"
        );
    }

    /**
     * Run a command on remote host
     * @return \Symfony\Component\Process\Process
     */
    public function runCommand(Server $server, string $command)
    {
        return $this->execProcess(
            "ssh -o StrictHostKeyChecking=no root@{$server->ip_address} -i ~/.ssh/{$server->slug} {$command}"
        );
    }

    /**
     * Run the add-ssh-key script on a server
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runAddSshkeyScript(Server $server, Sshkey $key)
    {
        $arguments = "'{$key->key}'";

        $scriptPath = base_path('scripts/add-ssh-key.sh');

        return $this->execProcess(
            "ssh -o StrictHostKeyChecking=no root@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptPath} {$arguments}"
        );
    }

    /**
     * Install ghost blog on server
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runInstallGhostScript(
        Server $server,
        Site $site,
        $credentials = [],
        $callback
    ) {
        // SITE_NAME=$1

        // MYSQL_USER=$2
        // MYSQL_PASSWORD=$3
        // MYSQL_DATABASE=$4

        // SITE_PORT_A=$5
        // SITE_PORT_B=$6

        // generate a mysql database and user for this ghost blog
        $this->runCreateDatabaseScript($server, 'mysql', $credentials);

        $scriptPath = base_path('scripts/sites/install-ghost.sh');

        $arguments = "{$site->name} {$credentials['username']} {$credentials['password']} {$credentials['database']} {$site->environment['PORTS'][0]} {$site->environment['PORTS'][1]}";

        return $this->execProcessAsync(
            "ssh -o StrictHostKeyChecking=no root@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptPath} {$arguments}",
            $callback
        );
    }

    /**
     * Run a command on a server to verify the server is ready to be used.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function verifyServerIsReady(Server $server)
    {
        $scriptPath = base_path('scripts/server/verify-is-ready.sh');

        return $this->execProcess(
            "ssh -o StrictHostKeyChecking=no espectra@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptPath}"
        );
    }

    /**
     * Run a command on a server to generate and get an ssh key.
     *
     * @return string
     */
    public function generateSshkeyOnServer(Server $server)
    {
        $scriptPath = base_path('scripts/server/generate-ssh-key.sh');

        $process = $this->execProcess(
            "ssh -o StrictHostKeyChecking=no espectra@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptPath}"
        );

        if ($process->isSuccessful()) {
            $key = explode('ssh-rsa', $process->getOutput())[1];

            $key = trim("ssh-rsa{$key}");

            $server->update([
                'ssh_key' => $key
            ]);

            return $key;
        }

        return null;
    }

    /**
     * Run create database script
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runCreateDatabaseScript(
        Server $server,
        string $type,
        array $credentials
    ) {
        // $arguments = "{$server->script}";
        // DATABASE_NAME=$1
        // DATABASE_USER=$2
        // DATABASE_PASSWORD=$3

        $database = $credentials['database'];
        $password = $credentials['password'];
        $username = $credentials['username'];

        switch ($type):
            case 'mysql':
                $scriptPath = base_path('scripts/server/add-mysql-database.sh');
                break;
        endswitch;

        $arguments = "{$database} {$username} {$password}";

        return $this->execProcess(
            "ssh -o StrictHostKeyChecking=no root@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptPath} {$arguments}"
        );
    }

    /**
     * Run the create-site script on a server
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runCreateServerScript(Server $server, Site $site)
    {
        $scriptPath = base_path('scripts/sites/add-site.sh');

        $wildcard_subdomains = (string) $site->wild_card_subdomains;

        // SITE_NAME=$1
        // WILD_CARD=$2
        // SITE_PORT_A=$3
        // SITE_PORT_=B$4
        $arguments = "{$site->name} {$wildcard_subdomains} {$site->environment['PORTS'][0]} {$site->environment['PORTS'][1]}";

        return $this->execProcess(
            "ssh -o StrictHostKeyChecking=no root@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptPath} {$arguments}"
        );
    }

    /**
     * Run the generate-port script on a server
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runGeneratePortScript(Server $server)
    {
        $scriptPath = base_path('scripts/sites/generate-port.sh');

        return $this->execProcess(
            "ssh -o StrictHostKeyChecking=no root@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptPath}"
        );
    }

    /**
     * Run the generate-sshkey script on a server
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runGenerateSshKey(Server $server)
    {
        $scriptPath = base_path('scripts/server/generate-ssh-key.sh');

        return $this->execProcess(
            "ssh -o StrictHostKeyChecking=no root@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptPath}"
        );
    }

    /**
     * Install a repository for a site on a server
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runInstallGitRepositoryScript(Server $server, Site $site)
    {
        $scriptPath = base_path('scripts/sites/install-repository.sh');

        $repoUrl = $site->getSshUrl();

        $arguments = "{$site->name} {$repoUrl}";

        return $this->execProcess(
            "ssh -o StrictHostKeyChecking=no espectra@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptPath} {$arguments}"
        );
    }

    /**
     * Run the script to install mysql database
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runInstallDatabaseScript(
        Server $server,
        string $database,
        string $password
    ) {
        $arguments = "{$password}";

        $scriptPath = 'scripts/databases';

        switch ($database) {
            case 'mysql':
                $scriptPath = "{$scriptPath}/add-mysql-db.sh";
                break;
            case 'mysql8':
                $scriptPath = "{$scriptPath}/add-mysql-8-db.sh";
                break;
            case 'mongodb':
                $scriptPath = "{$scriptPath}/add-mongo-db.sh";
                break;
            case 'postgres':
                $scriptPath = "{$scriptPath}/add-postgres-db.sh";
                break;
            case 'mariadb':
                $scriptPath = "{$scriptPath}/add-maria-db.sh";
                break;
            default:
                break;
        }

        $scriptName = base_path($scriptPath);

        return $this->execProcess(
            "ssh -o StrictHostKeyChecking=no root@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptName} {$arguments}"
        );
    }

    /**
     * Run the script to acquire a new certificate with certbot
     *
     */
    public function runAddCertificateScript($server, string $site)
    {
        $arguments = "{$site}";

        $scriptPath = 'scripts/sites/add-certificate.sh';

        $scriptName = base_path($scriptPath);

        return $this->execProcess(
            "ssh -o StrictHostKeyChecking=no root@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptName} {$arguments}"
        );
    }

    /**
     * Build the arguments for a script
     *
     * @return string
     */
    public function buildArgumentsFor(string $scriptName, $server = null)
    {
        switch ($scriptName) {
            case 'init':
                /**
                 * The first argument is SERVER_NAME
                 * Second argument is the SERVER_IP
                 * Third argument is SUDO_PASSWORD
                 */
                $sudo_password = str_random(12);
                return "{$sudo_password}";
            default:
                return '';
        }
    }

    /**
     * Generate the user data for init server
     *
     * @return string
     */
    public function getUserData(Server $server)
    {
        return (new Init($server))->generate();
    }

    /**
     * Generate the user data for initializing a vultr server.
     * Vultr requires the script be saved on the API
     *
     * @return string
     */
    public function getUserDataForVultr(Server $server, $credential = null)
    {
        return $this->getVultrConnectionInstance($credential->apiKey)
            ->startupscripts()
            ->create(
                USER_NAME . 'Initialize server script',
                $this->getUserData($server)
            )->SCRIPTID;
    }

    /**
     * Run deploy script for a site
     *
     * @return Process
     */
    public function runDeployScriptForSite(Site $site)
    {
        $server = $site->server;

        $scriptPath = $this->createDeployScript($site->getDeployScript());

        return $this->execProcessAsync(
            "ssh -o StrictHostKeyChecking=no espectra@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -is' -- < {$scriptPath}",
            function ($data) {
                echo $data;
            }
        );
    }

    /**
     *
     * Install nvm/node
     *
     * @return Process
     */
    public function runInstallNvmScript(Site $site)
    {
        $server = $site->server;

        $scriptPath = 'scripts/server/install-nvm.sh';

        $scriptName = base_path($scriptPath);

        return $this->execProcess(
            "ssh -o StrictHostKeyChecking=no espectra@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$scriptName}"
        );
    }

    /**
     * Create a public key file and return path to file
     *
     * @return string
     */
    public function createDeployScript($script)
    {
        $file = str_random(60);

        Storage::disk('local')->put("deploy_scripts/{$file}.sh", $script);

        return storage_path("app/deploy_scripts/{$file}.sh");
    }
}
