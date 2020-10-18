<?php

namespace Bahdcoder\Vultr\Api;

use GuzzleHttp\Client;

class Server extends Api
{
    /**
     * The api client
     *
     * @var Client
     */
    protected $client;

    /**
     * Server api
     *
     * @return
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new server on vultr
     *
     * @param string $hostname hostname of server
     * @param string $region region where new server should be deployed
     * @param int $os the operation system to be deployed on virtual machine
     * @param array $keys array of keys ids to be attached to this server
     * @return
     */
    public function create(
        $hostname,
        $region,
        $size,
        $os,
        $keys = [],
        $scriptId,
        $options = []
    ) {
        return $this->getResult(
            $this->client->post('server/create', [
                'form_params' => array_merge(
                    [
                        'OSID' => $os,
                        'DCID' => $region,
                        'VPSPLANID' => $size,
                        'hostname' => $hostname,
                        'SSHKEYID' => implode(',', $keys),
                        'SCRIPTID' => $scriptId
                    ],
                    $options
                )
            ])
        );
    }

    /**
     * Get a list of all servers
     *
     * @return object
     */
    public function list()
    {
        return $this->getResult($this->client->get('server/list'));
    }

    /**
     *
     * Get a single server
     */
    public function get(string $identifier)
    {
        return $this->getResult(
            $this->client->get("server/list?SUBID={$identifier}")
        );
    }
}
