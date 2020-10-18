<?php

namespace Bahdcoder\DigitalOcean\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Region extends Api
{
    /**
     * The api client
     *
     * @var Client
     */
    protected $client;

    /**
     * Initialize guzzle client
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get a list of all regions
     *
     * @return
     */
    public function getAll()
    {
        return $this->getResult($this->client->get('regions'));
    }
}
