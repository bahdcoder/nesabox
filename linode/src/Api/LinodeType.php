<?php

namespace Bahdcoder\Linode\Api;

use GuzzleHttp\Client;

class LinodeType extends Api
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
     * Get a list of all linode types
     *
     * @return object
     *
     */
    public function list()
    {
        return $this->getResult($this->client->get('linode/types'));
    }
}
