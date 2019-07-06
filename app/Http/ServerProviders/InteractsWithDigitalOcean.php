<?php

namespace App\Http\ServerProviders;

use Bahdcoder\DigitalOcean\DigitalOcean;
use GuzzleHttp\Exception\GuzzleException;

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
        try {
            return $this->getDigitalOceanConnectionInstance($token)
                ->region()
                ->getAll();
        } catch (GuzzleException $e) {
            return false;
        }
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
                ->getDefaultCredentialsFor('digital-ocean')->apiToken
        )
            ->droplet()
            ->getById($dropletId);
    }
}
