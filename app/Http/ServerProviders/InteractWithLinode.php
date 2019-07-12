<?php

namespace App\Http\ServerProviders;

use Bahdcoder\Linode\Linode;
use GuzzleHttp\Exception\GuzzleException;
use App\Server;
use App\Scripts\Server\Init;

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
     * Get stack script for linode
     *
     * @return string
     */
    public function getStackScriptForLinode(Server $server, $credential)
    {
        try {
            return $this->getLinodeConnectionInstance($credential->accessToken)
                ->stackScript()
                ->create(
                    SSH_USER . ' Stackscript',
                    ['linode/ubuntu18.04'],
                    (new Init($server))->generate()
                )->id;
        } catch (GuzzleException $e) {
            return false;
        }
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

    public function getLinode($id)
    {
        $credential = $this->getAuthUserCredentialsFor(LINODE);

        return $this->getLinodeConnectionInstance($credential->accessToken)
            ->linode()
            ->get($id);
    }
}
