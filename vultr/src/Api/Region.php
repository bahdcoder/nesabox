<?php

namespace Bahdcoder\Vultr\Api;

use GuzzleHttp\Client;

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
     * Get the list of all regions
     *
     * @return object
     */
    public function list()
    {
        return $this->getResult($this->client->get('regions/list'));
    }
}
