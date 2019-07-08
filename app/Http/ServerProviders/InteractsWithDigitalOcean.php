<?php

namespace App\Http\ServerProviders;

use Bahdcoder\DigitalOcean\DigitalOcean;
use GuzzleHttp\Exception\GuzzleException;
use App\Exceptions\InvalidProviderCredentials;

trait InteractsWithDigitalOcean
{
    /**
     * This method tests the digital ocean connection with the token provided
     *
     * @param string $token
     *
     * @return boolean
     */
    public function verifySuccessfulDigitalOceanConnection(string $token)
    {
        return $this->getDigitalOceanRegions($token);
    }

    /**
     * This method creates a new connection to digital ocean
     *
     * @return object
     */
    public function getDigitalOceanConnectionInstance(string $token)
    {
        return new DigitalOcean($token);
    }

    /**
     * This method gets a single droplet from digital ocean.
     *
     * @return object
     */
    public function getDigitalOceanDroplet(string $dropletId)
    {
        return $this->getDigitalOceanConnectionInstance(
            auth()
                ->user()
                ->getDefaultCredentialsFor(DIGITAL_OCEAN)->apiToken
        )
            ->droplet()
            ->getById($dropletId);
    }
}
