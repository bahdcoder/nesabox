<?php

namespace Bahdcoder\DigitalOcean\Api;

use GuzzleHttp\Client;

class DomainRecord extends Api
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
     * Create a new domain record
     *
     * @return object
     */
    public function create($domain, $data)
    {
        return $this->getResult(
            $this->client->post("domains/{$domain}/records", [
                'json' => $data
            ])
        )->domain_record;
    }

    /**
     * Update an existing domain record
     *
     * @return object
     */
    public function update($domain, $recordId, $data)
    {
        return $this->getResult(
            $this->client->put("domains/{$domain}/records/{$recordId}", [
                'json' => $data
            ])
        );
    }
}
