<?php

namespace App\Http\ServerProviders;

use App\Server;
use Bahdcoder\Linode\Linode;
use GuzzleHttp\Exception\GuzzleException;

trait InteractWithLinode
{
    /**
     * This method tests the linode connection with the token provided
     *
     * @param string $token
     *
     * @return boolean
     */
    public function verifySuccessfullLinodeConnection(string $token)
    {
        try {
            return $this->getLinodeConnectionInstance($token)
                ->linode()
                ->list();
        } catch (GuzzleException $e) {
            return false;
        }
    }

    /**
     * Generate the user data for init server
     *
     * @return string
     */
    public function getLinodeUserData(Server $server)
    {
        $deploy_script_route = config('app.url') . route('servers.custom-deploy-script', [
            $server->id,
            'api_token' => $server->user->api_token
        ], false);

        return <<<EOD
#!/bin/bash

curl -Ss '{$deploy_script_route}' >/tmp/nesabox.sh && bash /tmp/nesabox.sh
EOD;
    }

    /**
     * Get stack script for linode
     *
     * @return string
     */
    public function getStackScriptForLinode(Server $server, $credential)
    {
        return $this->getLinodeConnectionInstance($credential->accessToken)
            ->stackScript()
            ->create(
                SSH_USER . ' Stackscript',
                ['linode/ubuntu18.04'],
                $this->getLinodeUserData($server)
            )->id;
    }

    /**
     * This method creates a new connection to digital ocean
     *
     * @return object
     */
    public function getLinodeConnectionInstance(string $token)
    {
        return new Linode($token);
    }

    public function getLinode($id, $user = null, $credential_id = null)
    {
        return $this->getLinodeConnectionInstance(
            ($user ? $user : auth()->user())->getDefaultCredentialsFor(
                LINODE,
                $credential_id
            )->accessToken
        )
            ->linode()
            ->get($id);
    }
}
