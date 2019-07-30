<?php

namespace App\Http\ServerProviders;

use App\Site;
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
     * This method creates a new domain record
     *
     * @return object
     */
    public function createDomainRecord(Site $site)
    {
        return $this->getDigitalOceanConnectionInstance(
            config('services.digital-ocean.api-token')
        )
            ->domainRecord()
            ->create(config('services.digital-ocean.app-domain'), [
                'type' => 'A',
                'name' => $site->slug,
                'data' => $site->server->ip_address
            ]);
    }

    /**
     * This method gets a single droplet from digital ocean.
     *
     * @return object
     */
    public function getDigitalOceanDroplet(string $dropletId, $user = null)
    {
        return $this->getDigitalOceanConnectionInstance(
            ($user ? $user : auth()->user())->getDefaultCredentialsFor(
                DIGITAL_OCEAN
            )->apiToken
        )
            ->droplet()
            ->getById($dropletId);
    }

    /** */
    public function getDigitalOceanSizesForRegion(string $region)
    {
        // Get JSON content of do cache
        $data = cached_provider_data(DIGITAL_OCEAN);

        if (
            !collect($data->regions)->first(function ($r) use ($region) {
                return $r->slug === $region;
            })
        ) {
            abort(400, 'Invalid region for getting digital ocean sizes.');
        }

        return collect($data->sizes)
            ->filter(function ($size) use ($region) {
                return !in_array($region, $size->regions);
            })
            ->values();
    }
}
