<?php

namespace Bahdcoder\DigitalOcean;

use GuzzleHttp\Client;
use Bahdcoder\DigitalOcean\Api\Key;
use Bahdcoder\DigitalOcean\Api\Region;
use Bahdcoder\DigitalOcean\Api\Droplet;

class DigitalOcean
{
    /**
     * The http client for making api requests
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The access token for interfacing with DigitalOcean
     *
     * @var string
     */
    protected $accessToken;

    /**
     * Initialize DigitalOcean instance
     *
     * @return void
     */
    public function __construct(string $accessToken, Client $client = null)
    {
        $this->accessToken = $accessToken;

        $this->client = $client
            ? $client
            : new Client([
                'base_uri' => 'https://api.digitalocean.com/v2/',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken
                ]
            ]);
    }

    /**
     * Get the account api
     *
     * @return \Bahdcoder\DigitalOcean\Api\Droplet
     */
    public function droplet()
    {
        return new Droplet($this->client);
    }

    /**
     *
     * Get the region api
     *
     * @return \Bahdcoder\DigitalOcean\Api\Region
     *
     */
    public function region()
    {
        return new Region($this->client);
    }

    /**
     *
     * Get the keys api
     *
     * @return \Bahdcoder\DigitalOcean\Api\Key
     *
     */
    public function key()
    {
        return new Key($this->client);
    }
}
