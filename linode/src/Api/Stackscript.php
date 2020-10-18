<?php

namespace Bahdcoder\Linode\Api;

use GuzzleHttp\Client;

class Stackscript extends Api
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
     * Create a new Stackscript
     *
     * @return object
     *
     */
    public function create($label, $images, $script, $is_public = false)
    {
        return $this->getResult(
            $this->client->post('linode/stackscripts', [
                'json' => [
                    'label' => $label,
                    'images' => $images,
                    'script' => $script,
                    'is_public' => $is_public
                ]
            ])
        );
    }
}
