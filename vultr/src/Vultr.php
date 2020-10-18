<?php

namespace Bahdcoder\Vultr;

use GuzzleHttp\Client;
use Bahdcoder\Vultr\Api\Plan;
use Bahdcoder\Vultr\Api\Region;
use Bahdcoder\Vultr\Api\Sshkey;
use Bahdcoder\Vultr\Api\Server;
use Bahdcoder\Vultr\Api\Account;
use Bahdcoder\Vultr\Api\Startupscript;

class Vultr
{
    /**
     * The http client for making api requests
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The access token for interfacing with vultr
     *
     * @var string
     */
    protected $accessToken;

    /**
     * Initialize vultr instance
     *
     * @return void
     */
    public function __construct(string $accessToken, Client $client = null)
    {
        $this->accessToken = $accessToken;

        $this->client = $client
            ? $client
            : new Client([
                'base_uri' => 'https://api.vultr.com/v1/',
                'headers' => [
                    'API-key' => $this->accessToken,
                    'Content-Type' => 'application/json'
                ]
            ]);
    }

    /**
     * Get the account api
     *
     * @return \Bahdcoder\Vultr\Api\Account
     */
    public function account()
    {
        return new Account($this->client);
    }

    /**
     * Get the plan api
     *
     * @return \Bahdcoder\Vultr\Api\Plan
     */
    public function plan()
    {
        return new Plan($this->client);
    }

    /**
     * Get the server api
     *
     * @return \Bahdcoder\Vultr\Api\Server
     */
    public function server()
    {
        return new Server($this->client);
    }

    /**
     * Get the sshkey api
     *
     * @return \Bahdcoder\Vultr\Api\Sshkey
     */
    public function sshkey()
    {
        return new Sshkey($this->client);
    }

    /**
     * Get the regions api
     *
     * @return \Bahdcoder\Vultr\Api\Region
     */
    public function region()
    {
        return new Region($this->client);
    }

    /**
     * Get the startup scripts api
     *
     * @return \Bahdcoder\Vultr\Api\Startupscript
     */
    public function startupscripts()
    {
        return new Startupscript($this->client);
    }
}
