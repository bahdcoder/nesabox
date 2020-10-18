<?php

namespace App\Http\ServerProviders;

use Bahdcoder\Vultr\Vultr;
use GuzzleHttp\Exception\GuzzleException;

trait InteractsWithVultr
{
    /**
     * This method tests the digital ocean connection with the token provided
     *
     * @param string $token
     *
     * @return boolean
     */
    public function verifySuccessfulVultrConnection(string $token)
    {
        try {
            return $this->getVultrConnectionInstance($token)
                ->account()
                ->info();
        } catch (GuzzleException $e) {
            return false;
        }
    }

    /**
     * This method creates a new connection to digital ocean
     * @return boolean
     */
    public function getVultrConnectionInstance(string $token): Vultr
    {
        return new Vultr($token);
    }

    /**
     * Get a vultr server
     *
     * @return array
     */
    public function getVultrServer(
        string $identifier,
        $user = null,
        $credential_id = null
    ) {
        return $this->getVultrConnectionInstance(
            ($user ? $user : auth()->user())->getDefaultCredentialsFor(
                VULTR,
                $credential_id
            )->apiKey
        )
            ->server()
            ->get($identifier);
    }
}
