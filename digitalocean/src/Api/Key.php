<?php

namespace Bahdcoder\DigitalOcean\Api;

use GuzzleHttp\Client;

class Key extends Api
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
    public function create(string $name, string $key)
    {
        return $this->getResult(
            $this->client->post('account/keys', [
                'json' => [
                    'name' => $name,
                    'public_key' => $key
                ]
            ])
        )->ssh_key;
    }
}
