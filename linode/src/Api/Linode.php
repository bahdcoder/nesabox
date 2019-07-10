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
        $label,
        $region,
        $image,
        $type,
        $stackscript_id,
        $root_pass,
        $swap_size = 512
    ) {
        return $this->getResult(
            $this->client->post('linode/instances', [
                'json' => [
                    'type' => $type,
                    'image' => $image,
                    'label' => $label,
                    'region' => $region,
                    'root_pass' => $root_pass,
                    'swap_size' => $swap_size,
                    'stackscript_id' => $stackscript_id
                ]
            ])
        );
    }

    /**
     * Get all linodes
     *
     * @return object
     *
     */
    public function list()
    {
        return $this->getResult($this->client->get('linode/instances'));
    }

    /**
     * Get a linode by id
     *
     * @return object
     *
     */
    public function get($id)
    {
        return $this->getResult($this->client->get('linode/instances/' . $id));
    }
}
