<?php

namespace Bahdcoder\Linode;

use GuzzleHttp\Client;
use Bahdcoder\Linode\Api\Region;
use Bahdcoder\Linode\Api\Linode as LinodeInstance;

class Linode
{
    /**
     * The http client for making api requests
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The access token for interfacing with Linode
     *
     * @var string
     */
    protected $accessToken;

    /**
     * Initialize Linode instance
     *
     * @return void
     */
    public function __construct(string $accessToken, Client $client = null)
    {
        $this->accessToken = $accessToken;

        $this->client = $client
            ? $client
            : new Client([
                'base_uri' => 'https://api.linode.com/v4/',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken
                ]
            ]);
    }

    /**
     * Get the account api
     *
     * @return \Bahdcoder\Linode\Api\Linode
     */
    public function linode()
    {
        return new LinodeInstance($this->client);
    }

    /**
     *
     * Get the region api
     *
     * @return \Bahdcoder\Linode\Api\Region
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
     * @return \Bahdcoder\Linode\Api\Key
     *
     */
    public function key()
    {
        return new Key($this->client);
    }
}
