<?php

namespace App\Http\ServerProviders;

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
     * This method creates a new connection to digital ocean
     *
     * @return object
     */
    public function getLinodeConnectionInstance(string $token)
    {
        return new Linode($token);
    }
}
