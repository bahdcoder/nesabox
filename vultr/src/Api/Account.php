<?php

namespace Bahdcoder\Vultr\Api;

use GuzzleHttp\Client;

class Account
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
     * Get the account info for current api key
     *
     * @return
     */
    public function info()
    {
        return $this->client->get('account/info');
    }
}
