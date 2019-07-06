<?php

namespace Bahdcoder\Vultr\Api;

use GuzzleHttp\Client;

class Sshkey extends Api
{
    /**
     * The api client
     *
     * @var \GuzzleHttp\Client
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
     * Get the list of all ssh keys
     *
     * @param string $name The name of the ssh key
     * @param string $key The ssh key to be added
     *
     * @return object
     */
    public function create(string $name, string $key)
    {
        return $this->getResult(
            $this->client->post('sshkey/create', [
                'form_params' => [
                    'name' => $name,
                    'ssh_key' => $key
                ]
            ])
        );
    }
}
