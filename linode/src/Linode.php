<?php

namespace Bahdcoder\Linode;

use GuzzleHttp\Client;
use Bahdcoder\Linode\Api\Region;
use Bahdcoder\Linode\Api\Linode as LinodeInstance;
use Bahdcoder\Linode\Api\LinodeType;
use Bahdcoder\Linode\Api\Stackscript;

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

    /**
     *
     * Get the linode type api
     *
     * @return \Bahdcoder\Linode\Api\LinodeType
     *
     */
    public function linodeType()
    {
        return new LinodeType($this->client);
    }

    public function stackScript()
    {
        return new Stackscript($this->client);
    }
}
