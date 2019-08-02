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
        $process->setIdleTimeout($timeOut);

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
        $process->setIdleTimeout($timeOut);

        $process->start();

        foreach ($process as $type => $data):
            $callback($data);
        endforeach;

        return $process;
    }

    /**
     * Run the add-ssh-key script on a server
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runAddSshkeyScript(Server $server, Sshkey $key)
    {
        $user = SSH_USER;
        $arguments = "{$user} '{$key->key}'";

        $scriptPath = base_path('scripts/add-ssh-key.sh');

        return $this->execProcess(
            $this->sshScript($server, $scriptPath, $arguments)
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
            $this->sshScript($server, $scriptPath, '', false)
        );
    }

    /**
     *
     * Generate an ssh command to run a script on
     * a server
     *
     * @return string
     */
    public function sshScript(
        Server $server,
        $script,
        $arguments = '',
        $root = true
    ) {
        $user = $root ? 'root' : SSH_USER;

        return "ssh -o StrictHostKeyChecking=no {$user}@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -s' -- < {$script} {$arguments}";
    }

    /**
     * Run a command on a server to generate and get an ssh key.
     *
     * @return string
     */
    public function generateSshkeyOnServer(Server $server)
    {
        $scriptPath = base_path('scripts/server/generate-ssh-key.sh');

        $process = $this->execProcess($this->sshScript($server, $scriptPath));

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
            $this->sshScript($server, $scriptPath, $arguments)
        );
    }

    /**
     * Run the create-site script on a server
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runCreateSiteScript(Server $server, Site $site)
    {
        $scriptPath = base_path('scripts/sites/add-site.sh');

        $arguments = "{$site->name} {$site->getNexaboxSiteDomain()}";

        return $this->execProcess(
            $this->sshScript($server, $scriptPath, $arguments)
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

        return $this->execProcess($this->sshScript($server, $scriptPath));
    }

    /**
     * Run the generate-sshkey script on a server
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runGenerateSshKey(Server $server)
    {
        $scriptPath = base_path('scripts/server/generate-ssh-key.sh');

        return $this->execProcess($this->sshScript($server, $scriptPath));
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
            $this->sshScript($server, $scriptPath, $arguments, false)
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
            $this->sshScript($server, $scriptName, $arguments)
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
            $this->sshScript($server, $scriptName, $arguments)
        );
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
                SSH_USER . 'Initialize server script',
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

        $user = SSH_USER;

        $scriptPath = $this->createDeployScript($site->getDeployScript());

        return $this->execProcessAsync(
            "ssh -o StrictHostKeyChecking=no {$user}@{$server->ip_address} -i ~/.ssh/{$server->slug} 'bash -is' -- < {$scriptPath}",
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
            $this->sshScript($server, $scriptName, '', false)
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

    /**
     * Get the contents of a file from the server
     *
     * @return \Symphony\Process\Process
     */
    public function getFileContent(Server $server, $pathToFile)
    {
        $scriptPath = 'scripts/server/get-file-contents.sh';

        $scriptName = base_path($scriptPath);

        $arguments = "{$pathToFile}";

        return $this->execProcess(
            $this->sshScript($server, $scriptName, $arguments)
        );
    }

    /**
     * Update ghost config and restart ghost blog pm2 instance
     * 
     * @return \Symphony\Process\Process
     */
    public function updateGhostConfig(Server $server, Site $site, $config)
    {
        $scriptPath = 'scripts/sites/update-ghost-config.sh';

        $scriptName = base_path($scriptPath);

        $user = SSH_USER;

        $arguments = "{$user} {$site->name} '{$config}'";

        return $this->execProcess(
            $this->sshScript($server, $scriptName, $arguments)
        );
    }

    /**
     * Update the site slug
     * 
     * @return \Symphony\Process\Process
     */
    public function updateSiteSlug(Server $server, Site $site, string $slug)
    {
        $scriptPath = 'scripts/sites/update-site-slug.sh';

        $scriptName = base_path($scriptPath);

        $old_site_name = $site->getNexaboxSiteDomain();

        $new_site_name = $site->getNexaboxSiteDomain($slug);

        $arguments = "{$old_site_name} {$new_site_name} {$site->environment['PORT']}";

        return $this->execProcess(
            $this->sshScript($server, $scriptName, $arguments)
        );
    }
}
