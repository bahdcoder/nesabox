<?php

namespace Bahdcoder\Vultr\Api;

use GuzzleHttp\Client;

class Startupscript extends Api
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
     * Create a startupscript
     *
     * @param string $name The name of the script
     * @param string $script The script commands
     * @param string $type The script type. Defaults to boot.
     *
     * @return object
     */
    public function create(string $name, string $script, string $type = 'boot')
    {
        return $this->getResult(
            $this->client->post('startupscript/create', [
                'form_params' => [
                    'name' => $name,
                    'script' => $script,
                    'type' => $type
                ]
            ])
        );
    }
}
