<?php

namespace Bahdcoder\Linode\Api;

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
     * Fetch a list of all regions available to linode services
     *
     * @return void
     */
    public function list()
    {
        return $this->getResult($this->client->get('regions'));
    }
}
