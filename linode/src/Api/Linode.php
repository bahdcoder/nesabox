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
    public function create(
        $name,
        $region,
        $size,
        $image,
        $backups = false,
        $ipv6 = false,
        $privateNetworking = false,
        array $sshKeys = [],
        $userData = ''
    ) {
        return $this->getResult(
            $this->client->post('droplets', [
                'json' => [
                    'name' => $name,
                    'region' => $region,
                    'size' => $size,
                    'image' => $image,
                    'backups' => $backups,
                    'ipv6' => $ipv6,
                    'private_networking' => $privateNetworking,
                    'ssh_keys' => $sshKeys,
                    'user_data' => $userData
                ]
            ])
        )->droplet;
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
