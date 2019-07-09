<?php

namespace Bahdcoder\Linode\Api;

use GuzzleHttp\Client;

class Linode extends Api
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
     * Create a new Linode
     *
     * @return object
     *
     */
    public function create()
    {
    }

    /**
     * Get a droplet by id
     *
     * @return object
     *
     */
    public function list()
    {
        return $this->getResult($this->client->get('linode/instances'));
    }
}
