<?php

namespace App\Http\Traits;

use App\Server;
use Symfony\Component\Process\Exception\ProcessFailedException;

trait HandlesSshKeys
{
    /**
     * Get ssh key id for digital ocean. If not exists, create it.
     *
     * @return string the newly created key ID
     */
    public function getSshKeyForDigitalOcean(Server $server): string
    {
        $sshKey = $this->generateSshKeyForServer($server);

        $credential = $this->getAuthUserCredentialsFor(
            DIGITAL_OCEAN,
            $server->credential_id
        );

        // create an sshkey with digitalocean api
        $key = $this->getDigitalOceanConnectionInstance($credential->apiToken)
            ->key()
            ->create($server->slug, $sshKey->key);

        $sshKey->update([
            'provider' => DIGITAL_OCEAN,
            'fingerprint' => $key->fingerprint,
            'identifier' => $key->id
        ]);

        return $key->id;
    }

    /**
     * Get ssh key id for vultr. If not exists, create it.
     *
     * @return string the newly created key ID
     */
    public function getSshKeyForVultr(Server $server, $credential): string
    {
        $sshKey = $this->generateSshKeyForServer($server);

        $key = $this->getVultrConnectionInstance($credential->apiKey)
            ->sshkey()
            ->create($server->name, $sshKey->key);

        return $key->SSHKEYID;
    }

    /**
     * Generate an ssh key on espectra server for this specific server
     *
     * @return
     */
    public function generateSshKeyForServer(Server $server)
    {
        $slug = $server->slug;
        $app = config('app.host');

        $process = $this->execProcess(
            "ssh-keygen -f ~/.ssh/{$slug} -t rsa -b 4096 -P '' -C worker@{$app}"
        );

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $getKeyProcess = $this->execProcess("cat ~/.ssh/{$slug}.pub");

        if (!$getKeyProcess->isSuccessful()) {
            throw new ProcessFailedException($getKeyProcess);
        }

        return $server->sshkeys()->create([
            'name' => $slug,
            'is_app_key' => true,
            'is_ready' => true,
            'key' => trim($getKeyProcess->getOutput())
        ]);
    }
}
